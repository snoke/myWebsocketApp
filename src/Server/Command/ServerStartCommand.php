<?php

namespace App\Server\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Ratchet\Server\IoServer;
//use App\Server\WebsocketServer as AppServer;
use App\Api\ChatApi\ChatApi as AppServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;



#[AsCommand(
    name: 'server:start',
    description: 'Starts the Websocket Server',
)]
class ServerStartCommand extends Command
{    
    protected AppServer $server;
    const WS_PORT = 8080;
    const WSS_PORT = 8443;

    private ?int $port;

    public function __construct(AppServer $server) {
        parent::__construct();
        $this->server = $server;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('port', InputArgument::OPTIONAL, 'custom Port')
            ->addOption(
                'ssl',
                null,
                InputOption::VALUE_NONE,
                'use SSL (WSS Protocol)'
            )
        ;
    }

    private function createWsServer (HttpServer $httpServer) {
        $this->port = $this->port?$this->port:self::WS_PORT;
        return IoServer::factory(
            $httpServer,
            $this->port
        );
    }

    private function createWssServer (HttpServer $httpServer) {
        $this->port = $this->port?$this->port:self::WSS_PORT;
        $loop = \React\EventLoop\Factory::create();
        $server = new \React\Socket\Server('0.0.0.0:' .$this->port, $loop);
        $secureServer = new \React\Socket\SecureServer($server, $loop, [
            'local_cert'  => __DIR__  . '/../../config/ssl/certificate.crt',
            'local_pk' => __DIR__  . '/../../config/ssl/private.key',
            'verify_peer' => false,
        ]);
        
        return new IoServer($httpServer, $secureServer, $loop);

    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->port = $input->getArgument('port');

        $this->server->setInterface($input,$output);

        $io = new SymfonyStyle($input, $output);

        $httpServer = new HttpServer(new WsServer($this->server));

        if ($input->getOption('ssl')) {
            $server = $this->createWssServer($httpServer);
            $io->success('Secured Websocket Server started on Port ' . $this->port);

        } else {
            $server = $this->createWsServer($httpServer);
            $io->success('Websocket Server started on Port ' . $this->port);
        }


        $server->run();
        
        return Command::SUCCESS;
    }
}
