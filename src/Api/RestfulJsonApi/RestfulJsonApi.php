<?php
namespace App\Api\JwtSubscriberApi;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\ArrayInput;

use \Ratchet\WebSocket\WsConnection;
use App\Api\RestfulJsonApi\CommandCollection;
use App\Api\JsonApi\JsonCommandResponse;
use App\Api\JsonApi\Worker\CallbackResponder as Responder;
//use App\Api\JsonApi\Worker\BroadcastResponder as Responder;

use App\Api\JsonApi\JsonApi;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\User;

/**
 * this api extends jsonapi and provides restful commands.
 */
class RestfulJsonApi extends JsonApi {

    private array $workers;
    private CommandCollection $commands;

    public function __construct(
        CommandCollection $commands,
        Responder $responder,
        EntityCollection $entities,
    ) {
        parent::__construct($commands,$responder);
    }

}