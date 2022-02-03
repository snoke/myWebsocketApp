<?php
namespace App\Api;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use \Ratchet\WebSocket\WsConnection;
use App\Api\UserBroadcaster as Pusher;
use App\Api\ChatApiCommandContainer;
use App\Api\JsonCommandResponse;
use App\Api\JsonCommandRequest;

use Symfony\Component\DependencyInjection\ContainerInterface;
class ChatApi {

    private Pusher $pusher;
    private ChatApiCommandContainer $commands;

    public function __construct(
        ChatApiCommandContainer $commands,
        UserAuthListener $listener,
        Pusher $pusher,
    ) {
        $this->commands = $commands;
        $this->pusher = $pusher;
        $this->listener = $listener;
    }

    private function execute(CommandInterface $command, ArrayInput $params) {
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

    private function listen(WsConnection $from,CommandInterface $command,JsonCommandResponse $response) {
        $user = $this->listener->listen($command,$response);
        if ($user) {
            $this->pusher->addClient($from,$user);
        } 
    }

    private function push(WsConnection $from,CommandInterface $command,JsonCommandResponse $response) {
        $this->pusher->push($from,$command,$response);
    }

    public function run(WsConnection $from,string $json) {
        $request = new JsonCommandRequest($from,$json);
        $command = $this->commands->find($request->getAction());

        $response = $this->execute($command,new ArrayInput($request->getParams()));
        
        $this->listen($from,$command,$response);

        $this->push($from,$command,$response);

        return $this->response; 
    }

}