<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\JwtSubscriberApi;

use App\Api\JsonCommandRequest;
use App\Api\JwtSubscriberApi\Worker\JwtAuthListener as Authenticator;
use App\Api\JwtSubscriberApi\Worker\SubscriberBroadcastResponder as BroadcastPusher;
use App\Api\JsonApi\Worker\CallbackResponder;

use App\Api\JsonApi\JsonApi;

/**
 * this api provides an abstract class for commands to be able to add subscribers.
 * it also listens for Jwt Authentification to match connections to users
 *
 * command results will be broadcasted to those subscribers defined in your command
 */
class JwtSubscriberApi extends JsonApi
{

    private array $workers;
    private CommandCollection $commands;

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