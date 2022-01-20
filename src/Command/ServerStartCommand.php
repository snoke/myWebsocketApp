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

#[AsCommand(
    name: 'server:start',
    description: 'Add a short description for your command',
)]
class ServerStartCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $port = 8080;
        
        $io->success('Server running on Port ' . $port);

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Server($input,$output,$this->getApplication())
                )
            ),
            $port
        );
        
        $server->run();

        return Command::SUCCESS;
    }
}
