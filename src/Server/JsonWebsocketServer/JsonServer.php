<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JsonWebsocketServer;

use App\Server\JsonWebsocketServer\AbstractWorker as Worker;
use App\Server\JsonWebsocketServer\Worker\CallbackResponder;
use App\Server\WebsocketServer\WebsocketServer;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

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
class JsonServer extends WebsocketServer
{

    private array $workers;
    private CommandCollection $commands;

    /**
     * @param CommandCollection $commands
     * @param CallbackResponder $callbackResponder
     */
    public function __construct(
        CommandCollection $commands,
        CallbackResponder $callbackResponder,
    )
    {
        parent::__construct();
        $this->commands = $commands;
        $this->workers = [$callbackResponder];
    }

    /**
     * @param Command $command
     * @param ArrayInput $params
     * @return JsonCommandResponse
     * @throws ExceptionInterface
     */
    private function execute(Command $command, ArrayInput $params): JsonCommandResponse
    {
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

    /**
     * @param AbstractWorker $worker
     * @return void
     */
    protected function addWorker(Worker $worker): void
    {
        $this->workers[] = $worker;
    }

    /**
     * @param WsConnection $from
     * @param string $json
     * @return mixed
     */
    public function run(WsConnection $from, string $json): mixed
    {
        return $this->response;
    }

    /**
     * @param ConnectionInterface $from
     * @return void
     */
    public function onOpen(ConnectionInterface $from): void
    {
        parent::onOpen($from);
        foreach ($this->workers as $worker) {
            $worker->onOpen($from);
        }
    }

    /**
     * @param ConnectionInterface $from
     * @return void
     */
    public function onClose(ConnectionInterface $from): void
    {
        parent::onClose($from);
        foreach ($this->workers as $worker) {
            $worker->onClose($from);
        }
    }

    /**
     * @param ConnectionInterface $from
     * @param $json
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $json): void
    {
        parent::onMessage($from, $json);
        $request = new JsonCommandRequest($from, $json);
        $command = $this->commands->find($request->getAction());
        $response = $this->execute($command, new ArrayInput($request->getParams()));
        foreach ($this->workers as $worker) {
            $worker->onMessage($from, $command, $response);
        }
        $this->io->block($response, 'SERVER RESPONSE', 'fg=green', ' ', true);
    }

}