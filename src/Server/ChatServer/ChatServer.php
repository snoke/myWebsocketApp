<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer;

use App\Server\JsonWebsocketServer\Worker\CallbackResponder;
use App\Server\JwtSubscriberServer\JwtSubscriberServer as Server;
use App\Server\JwtSubscriberServer\Worker\JwtAuthListener as Authenticator;
use App\Server\JwtSubscriberServer\Worker\SubscriberBroadcastResponder as BroadcastPusher;

/**
 * ChatServer
 */
class ChatServer extends Server
{
    private array $workers;
    private CommandCollection $commands;

    /**
     * @param CommandCollection $commands
     * @param CallbackResponder $callbackResponder
     * @param Authenticator $authenticator
     * @param BroadcastPusher $broadcastPusher
     */
    public function __construct(
        CommandCollection $commands,
        CallbackResponder $callbackResponder,
        Authenticator     $authenticator,
        BroadcastPusher   $broadcastPusher,
    )
    {
        parent::__construct($commands, $callbackResponder, $authenticator, $broadcastPusher);
    }

}