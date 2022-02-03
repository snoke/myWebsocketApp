<?php
namespace App\Api;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use \Ratchet\WebSocket\WsConnection;
use App\Api\UserBroadcaster as Pusher;

use App\Api\Command\AuthLoginCommand;
use App\Api\Command\AuthRegisterCommand;
use App\Api\Command\AuthTokenDecodeCommand;
use App\Api\Command\ChatBlockCommand;
use App\Api\Command\ChatLoadCommand;
use App\Api\Command\ChatLoadUserchatsCommand;
use App\Api\Command\ChatMessageSendCommand;
use App\Api\Command\ChatMessageStatusCommand;
use App\Api\Command\ChatTypingCommand;
use App\Api\Command\ChatUnblockCommand;
use App\Api\Command\ContactAddCommand;
use App\Api\Command\ContactSearchCommand;
use App\Api\Command\FileUploadCommand;
use App\Api\Command\UserChangePasswordCommand;
use App\Api\Command\UserContactsCommand;
use App\Api\Command\ChatLoadMessagesCommand;


class ChatApi {
    private Pusher $pusher;
    private array $commands;
    
    private function find(string $action) {
        foreach($this->commands as $command) {
            if ($command->getName()==$action) {
                return $command;
            }
        }
    }
    public function __construct(

        UserAuthListener $listener,
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
        ChatLoadMessagesCommand $chatLoadMessages,
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
             $chatLoadMessages,

        ];
    }
    public function run(WsConnection $from,string $json) {
        $body = json_decode($json, true); 
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