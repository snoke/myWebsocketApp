<?php
namespace App\Websocket;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

use App\Websocket\Command\AuthLoginCommand;
use App\Websocket\Command\AuthRegisterCommand;
use App\Websocket\Command\AuthTokenDecodeCommand;
use App\Websocket\Command\ChatBlockCommand;
use App\Websocket\Command\ChatLoadCommand;
use App\Websocket\Command\ChatLoadUserchatsCommand;
use App\Websocket\Command\ChatMessageSendCommand;
use App\Websocket\Command\ChatMessageStatusCommand;
use App\Websocket\Command\ChatTypingCommand;
use App\Websocket\Command\ChatUnblockCommand;
use App\Websocket\Command\ContactAddCommand;
use App\Websocket\Command\ContactSearchCommand;
use App\Websocket\Command\FileUploadCommand;
use App\Websocket\Command\UserChangePasswordCommand;
use App\Websocket\Command\UserContactsCommand;

class Api {
    private $data;
    private function find($action) {
        foreach($this->commands as $command) {
            if ($command->getName()==$action) {
                return $command;
            }
        }
    }
    private $commands;

    public function __construct(
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
        /*
             */
        ) {

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
             /*
             */

        ];
    }
    public function getData() {
        return $this->data;
    }
    public function run(/*$pusher, */$action, InputInterface $arguments, $output) {
        $command = $this->find($action);
        $statusCode = $command->run($arguments, $output);
        /*if ($pusher) {
            $pusher->push($command,$this->data);
        } */
        return $statusCode;
    }

}