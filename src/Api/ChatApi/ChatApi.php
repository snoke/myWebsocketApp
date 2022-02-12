<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\ChatApi;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use \Ratchet\WebSocket\WsConnection;
use App\Api\ChatApi\CommandCollection;
use App\Api\JwtSubscriberApi\Worker\JwtAuthListener as Authenticator;
use App\Api\JwtSubscriberApi\Worker\SubscriberBroadcastResponder as BroadcastPusher;

use App\Api\JsonApi\Worker\CallbackResponder;
use App\Api\JwtSubscriberApi\JwtSubscriberApi as Api;

use Symfony\Component\DependencyInjection\ContainerInterface;
class ChatApi extends Api {

    private array $workers;
    private CommandCollection $commands;

    public function __construct(
        CommandCollection $commands,
        CallbackResponder $callbackResponder,
        Authenticator $authenticator,
        BroadcastPusher $broadcastPusher,
    ) {
        parent::__construct($commands,$callbackResponder,$authenticator,$broadcastPusher);
    }

}