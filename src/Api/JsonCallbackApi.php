<?php
namespace App\Api;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use App\Api\AbstractWorker as Worker;
use \Ratchet\WebSocket\WsConnection;
use App\Api\CommandCollection;
use App\Api\JsonCommandResponse;
use App\Api\JsonCommandRequest;
use App\Api\Worker\SubscriberBroadcastPusher;
use App\Api\Worker\CallbackPusher;
use App\Server\WebsocketServer;

use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Command\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;
class JsonCallbackApi extends WebsocketServer {

    private array $workers;
    private CommandCollection $commands;
    public function __construct(
        CommandCollection $commands,
        CallbackPusher $callbackPusher,
    ) {
        parent::__construct();
        $this->commands = $commands;
        $this->workers = [$callbackPusher];
    }

    private function execute(Command $command, ArrayInput $params) {
        $output = new BufferedOutput();
        $statusCode = $command->run($params, $output);
        $data = $output->fetch();
        return new JsonCommandResponse(
            $command->getName(),
            $params,
            $statusCode,
            $data
        );
    }

    protected function addWorker(Worker $worker) {
        $this->workers[] = $worker;
    }
    
    public function run(WsConnection $from,string $json) {
        return $this->response; 
    }

    public function onOpen(ConnectionInterface $from) {
        parent::onOpen($from);
        foreach($this->workers as $worker) {
            $worker->onOpen($from);
        }
    }
    public function onClose(ConnectionInterface $from) {
        parent::onClose($from);
        foreach($this->workers as $worker) {
            $worker->onClose($from);
        }
    }

    public function onMessage(ConnectionInterface $from,  $json) {
        parent::onMessage($from,  $json);
        $request = new JsonCommandRequest($from,$json);
        $command = $this->commands->find($request->getAction());
        $response = $this->execute($command,new ArrayInput($request->getParams()));
        foreach($this->workers as $worker) {
            $worker->onMessage($from,$command,$response);
        }
        $this->io->block($this->run( $from, $json), 'API RESPONSE', 'fg=blue', ' ', true);
    }

}