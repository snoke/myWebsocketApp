<?php
namespace App\Api;


use App\Api\Command;

class ChatApiCommandContainer {
    private array $commands;
    
    public function find(string $action):CommandInterface {
        foreach($this->commands as $command) {
            if ($command->getName()==$action) {
                return $command;
            }
        }
    }
    public function __construct(
        Command\AuthLoginCommand $authLogin,
        Command\AuthTokenDecodeCommand $authTokenDecodeCommand,
        Command\ChatLoadUserchatsCommand $chatLoadUserchatsCommand,
        Command\AuthRegisterCommand $authRegisterCommand,
        Command\ChatBlockCommand $chatBlockCommand,
        Command\ChatLoadCommand $chatLoadCommand,
        Command\ChatMessageSendCommand $chatMessageSendCommand,
        Command\ChatMessageStatusCommand $chatMessageStatusCommand,
        Command\ChatTypingCommand $chatTypingCommand,
        Command\ChatUnblockCommand $chatUnblockCommand,
        Command\ContactAddCommand $contactAddCommand,
        Command\ContactSearchCommand $contactSearchCommand,
        Command\FileUploadCommand $fileUploadCommand,
        Command\UserChangePasswordCommand $userChangePasswordCommand,
        Command\UserContactsCommand $userContactsCommand,
        Command\ChatLoadMessagesCommand $chatLoadMessages,
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
             $chatLoadMessages,
        ];
    }
}