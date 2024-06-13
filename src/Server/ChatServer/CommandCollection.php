<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer;

use App\Server\ChatServer\Command\ChatBlockCommand;
use App\Server\ChatServer\Command\ChatLoadCommand;
use App\Server\ChatServer\Command\ChatLoadMessagesCommand;
use App\Server\ChatServer\Command\ChatLoadUserchatsCommand;
use App\Server\ChatServer\Command\ChatMessageSendCommand;
use App\Server\ChatServer\Command\ChatMessageStatusCommand;
use App\Server\ChatServer\Command\ChatTypingCommand;
use App\Server\ChatServer\Command\ChatUnblockCommand;
use App\Server\ChatServer\Command\ContactAddCommand;
use App\Server\ChatServer\Command\ContactSearchCommand;
use App\Server\ChatServer\Command\FileUploadCommand;
use App\Server\ChatServer\Command\UserChangePasswordCommand;
use App\Server\ChatServer\Command\UserContactsCommand;
use App\Server\JwtSubscriberServer\Command\AuthLoginCommand;
use App\Server\JwtSubscriberServer\Command\AuthRegisterCommand;
use App\Server\JwtSubscriberServer\Command\AuthTokenDecodeCommand;
use App\Server\JwtSubscriberServer\CommandCollection as Base;

/**
 * CommandCollection
 */
class CommandCollection extends Base
{

    /**
     * @param AuthLoginCommand $authLogin
     * @param AuthRegisterCommand $authRegisterCommand
     * @param AuthTokenDecodeCommand $authTokenDecodeCommand
     * @param ChatLoadUserchatsCommand $chatLoadUserchatsCommand
     * @param ChatBlockCommand $chatBlockCommand
     * @param ChatLoadCommand $chatLoadCommand
     * @param ChatMessageSendCommand $chatMessageSendCommand
     * @param ChatMessageStatusCommand $chatMessageStatusCommand
     * @param ChatTypingCommand $chatTypingCommand
     * @param ChatUnblockCommand $chatUnblockCommand
     * @param ContactAddCommand $contactAddCommand
     * @param ContactSearchCommand $contactSearchCommand
     * @param FileUploadCommand $fileUploadCommand
     * @param UserChangePasswordCommand $userChangePasswordCommand
     * @param UserContactsCommand $userContactsCommand
     * @param ChatLoadMessagesCommand $chatLoadMessages
     */
    public function __construct(
        AuthLoginCommand                                         $authLogin,
        AuthRegisterCommand                                      $authRegisterCommand,
        AuthTokenDecodeCommand                                   $authTokenDecodeCommand,

        \App\Server\ChatServer\Command\ChatLoadUserchatsCommand  $chatLoadUserchatsCommand,
        \App\Server\ChatServer\Command\ChatBlockCommand          $chatBlockCommand,
        \App\Server\ChatServer\Command\ChatLoadCommand           $chatLoadCommand,
        \App\Server\ChatServer\Command\ChatMessageSendCommand    $chatMessageSendCommand,
        \App\Server\ChatServer\Command\ChatMessageStatusCommand  $chatMessageStatusCommand,
        \App\Server\ChatServer\Command\ChatTypingCommand         $chatTypingCommand,
        \App\Server\ChatServer\Command\ChatUnblockCommand        $chatUnblockCommand,
        \App\Server\ChatServer\Command\ContactAddCommand         $contactAddCommand,
        \App\Server\ChatServer\Command\ContactSearchCommand      $contactSearchCommand,
        \App\Server\ChatServer\Command\FileUploadCommand         $fileUploadCommand,
        \App\Server\ChatServer\Command\UserChangePasswordCommand $userChangePasswordCommand,
        \App\Server\ChatServer\Command\UserContactsCommand       $userContactsCommand,
        \App\Server\ChatServer\Command\ChatLoadMessagesCommand   $chatLoadMessages,
    )
    {
        parent::__construct($authLogin, $authRegisterCommand, $authTokenDecodeCommand);

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