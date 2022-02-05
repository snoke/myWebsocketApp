<?php
namespace App\Api;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use App\Api\WorkerInterface as Worker;
use \Ratchet\WebSocket\WsConnection;
use App\Api\CommandCollection;
use App\Api\JsonCommandResponse;
use App\Api\JsonCommandRequest;
use App\Api\SubscriberBroadcastPusher;
use App\Api\CallbackPusher;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;
class JsonCallbackApi {

    private array $workers;
    private CommandCollection $commands;
    public function __construct(
        CommandCollection $commands,
        CallbackPusher $callbackPusher,
    ) {
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
        $request = new JsonCommandRequest($from,$json);
        $command = $this->commands->find($request->getAction());

        $response = $this->execute($command,new ArrayInput($request->getParams()));
        
        foreach($this->workers as $worker) {
            $worker->work($from,$command,$response);
        }

        return $this->response; 
    }

}