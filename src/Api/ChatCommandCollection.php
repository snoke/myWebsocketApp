<?php
namespace App\Api;

use App\Api\Command;

class ChatCommandCollection extends CommandCollection{
    
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
            parent::__construct();
            $this->addCommand($authLogin);
            $this->addCommand($authTokenDecodeCommand);
            $this->addCommand($chatLoadUserchatsCommand);
            $this->addCommand($authRegisterCommand);
            $this->addCommand($chatBlockCommand);
            $this->addCommand($chatLoadCommand);
            $this->addCommand($chatMessageSendCommand);
            $this->addCommand($chatMessageStatusCommand);
            $this->addCommand($chatTypingCommand);
            $this->addCommand($chatUnblockCommand);
            $this->addCommand($contactAddCommand);
            $this->addCommand($contactSearchCommand);
            $this->addCommand($fileUploadCommand);
            $this->addCommand($userChangePasswordCommand);
            $this->addCommand($userContactsCommand);
            $this->addCommand($chatLoadMessages);
    }
}