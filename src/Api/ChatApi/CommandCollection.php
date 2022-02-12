<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\ChatApi;

use App\Api\ChatApi\Command;

use App\Api\JwtSubscriberApi\Command\AuthLoginCommand;
use App\Api\JwtSubscriberApi\Command\AuthRegisterCommand;
use App\Api\JwtSubscriberApi\Command\AuthTokenDecodeCommand;

use App\Api\JwtSubscriberApi\CommandCollection as Base;

class CommandCollection extends Base {
    
    public function __construct(
        AuthLoginCommand $authLogin,
        AuthRegisterCommand $authRegisterCommand,
        AuthTokenDecodeCommand $authTokenDecodeCommand,

        Command\ChatLoadUserchatsCommand $chatLoadUserchatsCommand,
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
            parent::__construct($authLogin,$authRegisterCommand,$authTokenDecodeCommand);

            $this->addCommand($chatLoadUserchatsCommand);
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