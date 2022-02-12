<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Server;
use Ratchet\MessageComponentInterface;
use Ratchet\MessageInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebsocketServer implements MessageComponentInterface {
    protected \SplObjectStorage $clients;
    protected SymfonyStyle $io;

    public function __construct() {

        $this->clients = new \SplObjectStorage;
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
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        $this->io->block("Connection dropped $conn->remoteAddress ($conn->resourceId)", 'INFO', 'fg=yellow', ' ', true);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}