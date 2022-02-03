<?php
namespace App\Server;
use Ratchet\MessageComponentInterface;
use Ratchet\MessageInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Api\ChatApi as Api;

class WebsocketServer implements MessageComponentInterface {
    protected \SplObjectStorage $clients;
    protected Api $api;
    protected SymfonyStyle $io;

    public function __construct(Api $api) {

        $this->clients = new \SplObjectStorage;
        $this->api = $api;
    }

    public function setInterface(InputInterface $input, OutputInterface $output) {
        $this->io = new SymfonyStyle($input, $output);
    }
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->io->block("New connection from $conn->remoteAddress ($conn->resourceId)", 'INFO', 'fg=yellow', ' ', true);
    }
    public function onMessage(ConnectionInterface $from,  $msg) {
        $this->io->block($msg, 'USER REQUEST', 'fg=blue', ' ', true);
        $this->io->block($this->api->run($from,$msg), 'API RESPONSE', 'fg=green', ' ', true);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->io->block("Connection dropped $conn->remoteAddress ($conn->resourceId)", 'INFO', 'fg=yellow', ' ', true);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}