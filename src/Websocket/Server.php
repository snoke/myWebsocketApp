<?php
namespace App\Websocket;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\BufferedOutput;

class Server implements MessageComponentInterface {
    protected $clients;
    protected $application;
    protected $input;
    protected $output;

    public function __construct($input,$output,$application) {
        $this->clients = new \SplObjectStorage;
        $this->application = $application;
        $this->input = $input;
        $this->output = $output;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }


    private function actionController($client,$action,$params) {

        $arguments = new ArrayInput($params);
        $command = $this->application->find($action);
        $output = new BufferedOutput();
        $command->run($arguments, $output);
        $data = $output->fetch();
        return ["command"=>$action,"success"=>true,"data"=>$data];
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        
        $options = json_decode($msg, true);
        $from->send(json_encode($this->actionController($from,$options['action'],$options['params'])));
        /*
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
        */
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}