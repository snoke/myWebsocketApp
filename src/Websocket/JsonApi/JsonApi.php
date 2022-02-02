<?php
namespace App\Websocket\JsonApi;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

use App\Websocket\JsonApi\Command\AuthLoginCommand;
use App\Websocket\JsonApi\Command\AuthRegisterCommand;
use App\Websocket\JsonApi\Command\AuthTokenDecodeCommand;
use App\Websocket\JsonApi\Command\ChatBlockCommand;
use App\Websocket\JsonApi\Command\ChatLoadCommand;
use App\Websocket\JsonApi\Command\ChatLoadUserchatsCommand;
use App\Websocket\JsonApi\Command\ChatMessageSendCommand;
use App\Websocket\JsonApi\Command\ChatMessageStatusCommand;
use App\Websocket\JsonApi\Command\ChatTypingCommand;
use App\Websocket\JsonApi\Command\ChatUnblockCommand;
use App\Websocket\JsonApi\Command\ContactAddCommand;
use App\Websocket\JsonApi\Command\ContactSearchCommand;
use App\Websocket\JsonApi\Command\FileUploadCommand;
use App\Websocket\JsonApi\Command\UserChangePasswordCommand;
use App\Websocket\JsonApi\Command\UserContactsCommand;

use \Ratchet\WebSocket\WsConnection;


use App\Entity\User;

class JsonApi {
    private $pusher;
    private $commands;
    
    private function find($action) {
        foreach($this->commands as $command) {
            if ($command->getName()==$action) {
                return $command;
            }
        }
    }
    public function __construct(
        AuthListener $listener,
        Pusher $pusher,

        AuthLoginCommand $authLogin,
        AuthTokenDecodeCommand $authTokenDecodeCommand,
        ChatLoadUserchatsCommand $chatLoadUserchatsCommand,
        AuthRegisterCommand $authRegisterCommand,
        ChatBlockCommand $chatBlockCommand,
        ChatLoadCommand $chatLoadCommand,
        ChatMessageSendCommand $chatMessageSendCommand,
        ChatMessageStatusCommand $chatMessageStatusCommand,
        ChatTypingCommand $chatTypingCommand,
        ChatUnblockCommand $chatUnblockCommand,
        ContactAddCommand $contactAddCommand,
        ContactSearchCommand $contactSearchCommand,
        FileUploadCommand $fileUploadCommand,
        UserChangePasswordCommand $userChangePasswordCommand,
        UserContactsCommand $userContactsCommand,
        ) {

        $this->pusher = $pusher;
        $this->listener = $listener;

        $this->commands=[
             $authLogin,
             $authTokenDecodeCommand,
             $chatLoadUserchatsCommand,
             $authRegisterCommand,
             $chatBlockCommand,
             $chatLoadCommand,
             $chatMessageSendCommand,
             $chatMessageStatusCommand,
             $chatTypingCommand,
             $chatUnblockCommand,
             $contactAddCommand,
             $contactSearchCommand,
             $fileUploadCommand,
             $userChangePasswordCommand,
             $userContactsCommand,

        ];
    }
    public function run(WsConnection $from,string $msg) {
        $body = json_decode($msg, true); 
        $output = new BufferedOutput();
        $command = $this->find($body['action']);
        $statusCode = $command->run(new ArrayInput($body['params']), $output);
        $data = $output->fetch();

        $user = $this->listener->listen($command,$statusCode,$data);
        if ($user) {
            $this->pusher->addClient($from,$user);
        } 

        $this->pusher->push($from,$command,[
            "command"=>$command->getName(),
            "params"=>$body['params'],
            "status"=>$statusCode,
            "data"=>$data
        ]);
        return $data; 
    }

}