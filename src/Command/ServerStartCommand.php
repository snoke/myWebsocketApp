<?php

namespace App\Command;

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
use App\Websocket\Server as AppServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\Entity\Chat;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

#[AsCommand(
    name: 'server:start',
    description: 'Starts the Websocket Server',
)]
class ServerStartCommand extends AbstractCommand
{    
    const WS_PORT = 8080;
    const WSS_PORT = 8443;

    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer,JWTEncoderInterface $encoder) {
        parent::__construct($em,$serializer);
        $this->encoder = $encoder;
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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->em->clear();

        $chats = $this->em->getRepository(Chat::class);
        $port = $input->getArgument('port');
        $io = new SymfonyStyle($input, $output);

        $httpServer = new HttpServer(
            new WsServer(
                new AppServer($input,$output,$this->getApplication(),$this->em,$this->encoder)
            )
        );

        if ($input->getOption('ssl')) {
            $port = $port?$port:self::WSS_PORT;
        
            $loop = \React\EventLoop\Factory::create();
            $server = new \React\Socket\Server('0.0.0.0:' .$port, $loop);
            
            $secureServer = new \React\Socket\SecureServer($server, $loop, [
                'local_cert'  => __DIR__  . '/../../config/ssl/certificate.crt',
                'local_pk' => __DIR__  . '/../../config/ssl/private.key',
                'verify_peer' => false,
            ]);
            
            $server = new IoServer($httpServer, $secureServer, $loop);
            
            $io->success('WSS Server running on Port ' . $port);
        } else {
            $port = $port?$port:self::WS_PORT;
            
            $server = IoServer::factory(
                $httpServer,
                $port
            );

            $io->success('WS Server running on Port ' . $port);
            
        }
        $server->run();
        
        return Command::SUCCESS;

    }
}
