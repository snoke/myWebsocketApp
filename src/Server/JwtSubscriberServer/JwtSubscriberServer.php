<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer;

use App\Server\JsonWebsocketServer\JsonServer;
use App\Server\JsonWebsocketServer\Worker\CallbackResponder;
use App\Server\JwtSubscriberServer\Worker\JwtAuthListener as Authenticator;
use App\Server\JwtSubscriberServer\Worker\SubscriberBroadcastResponder as BroadcastPusher;

/**
 * this server provides an abstract class for commands to be able to add subscribers.
 * it also listens for Jwt Authentification to match connections to users
 *
 * command results will be broadcasted to those subscribers defined in your command
 */
abstract class JwtSubscriberServer extends JsonServer
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
        parent::__construct($commands, $callbackResponder);
        $this->addWorker($authenticator);
        $this->addWorker($broadcastPusher);
    }

}