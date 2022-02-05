<?php
namespace App\Api;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use \Ratchet\WebSocket\WsConnection;
use App\Api\ChatCommandCollection as CommandCollection;
use App\Api\JsonCommandResponse;
use App\Api\JsonCommandRequest;
use App\Api\Worker\JwtAuthListener as Authenticator;
use App\Api\Worker\SubscriberBroadcastPusher as BroadcastPusher;
use App\Api\Worker\CallbackPusher;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;
class ChatApi extends JsonCallbackApi {

    private array $workers;
    private CommandCollection $commands;

    public function __construct(
        CommandCollection $commands,
        CallbackPusher $callbackPusher,
        Authenticator $authenticator,
        BroadcastPusher $broadcastPusher,
    ) {
        parent::__construct($commands,$callbackPusher);
        $this->addWorker($authenticator);
        $this->addWorker($broadcastPusher);
    }

}