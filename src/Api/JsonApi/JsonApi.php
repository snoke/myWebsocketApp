<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use App\Api\JsonApi\AbstractWorker as Worker;
use \Ratchet\WebSocket\WsConnection;
use App\Api\JsonApi\CommandCollection;
use App\Api\JsonApi\JsonCommandResponse;
use App\Api\JsonApi\JsonCommandRequest;
use App\Api\JsonApi\Worker\CallbackResponder;
use App\Server\WebsocketServer;

use Ratchet\ConnectionInterface;
use Symfony\Component\Console\Command\Command;

/**
 *  Websocket Json Api to Symfony Commands
 * 
 * simply register Commands in CommandCollection ($this->commands->add($myCommand))
 * 
 * and websocket clients can call them using following json format
 *  
 *  example request:
 * { 
 *  action: 'auth:login',
 *  params:{
 *      loginName:'alice',
 *      password:'test'
 *      }
 *  }
 * 
 * response will look like:
 * {
*       command:'auth:login',
*       params:{
 *          loginName:'alice',
 *          password:'test'
 *      },
 *      "status":0, //Command Status Code
 *      "data":'SA0dsId1c3dF8DasdAD...', //token in this case, or whatever your command output is
 * }
 */
class JsonApi extends WebsocketServer {

    private array $workers;
    private CommandCollection $commands;
    public function __construct(
        CommandCollection $commands,
        CallbackResponder $callbackResponder,
    ) {
        parent::__construct();
        $this->commands = $commands;
        $this->workers = [$callbackResponder];
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
        $this->io->block($response, 'API RESPONSE', 'fg=green', ' ', true);
    }

}