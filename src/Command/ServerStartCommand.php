<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Ratchet\Server\IoServer;
use App\Websocket\Server;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Chat;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;


#[AsCommand(
    name: 'server:start',
    description: 'Starts the Websocket Server',
)]
class ServerStartCommand extends Command
{
    public function __construct(EntityManagerInterface $em,JWTEncoderInterface $encoder) {
        parent::__construct();
        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('port', InputArgument::OPTIONAL, 'provide a custom Port (default is 8080)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        
        $this->em->clear();
        $chats = $this->em->getRepository(Chat::class);
        $io = new SymfonyStyle($input, $output);
        $port = $input->getArgument('port');
        $port = $port?$port:8080;
        
        $io->success('Server running on Port ' . $port);

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Server($input,$output,$this->getApplication(),$chats,$this->encoder)
                )
            ),
            $port
        );
        
        $server->run();

        return Command::SUCCESS;
    }
}
