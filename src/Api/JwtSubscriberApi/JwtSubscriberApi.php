<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use \Ratchet\WebSocket\WsConnection;
use App\Api\JwtSubscriberApi\CommandCollection;
use App\Api\JsonApi\JsonCommandResponse;
use App\Api\JsonCommandRequest;
use App\Api\JwtSubscriberApi\Worker\JwtAuthListener as Authenticator;
use App\Api\JwtSubscriberApi\Worker\SubscriberBroadcastResponder as BroadcastPusher;
use App\Api\JsonApi\Worker\CallbackResponder;

use App\Api\JsonApi\JsonApi;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * this api provides an abstract class for commands to be able to add subscribers.
 * it also listens for Jwt Authentification to match connections to users 
 * 
 * command results will be broadcasted to those subscribers defined in your command
 */
class JwtSubscriberApi extends JsonApi {

    private array $workers;
    private CommandCollection $commands;

    public function __construct(
        CommandCollection $commands,
        CallbackResponder $callbackResponder,
        Authenticator $authenticator,
        BroadcastPusher $broadcastPusher,
    ) {
        parent::__construct($commands,$callbackResponder);
        $this->addWorker($authenticator);
        $this->addWorker($broadcastPusher);
    }

}