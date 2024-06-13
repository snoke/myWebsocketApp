<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer\Worker;

use App\Server\JsonWebsocketServer\AbstractWorker as Worker;
use App\Server\JsonWebsocketServer\JsonCommandResponse;
use App\Server\JwtSubscriberServer\AuthenticatedUserClientCollection;
use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand;
use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Console\Command\Command;

/**
 * SubscriberBroadcastResponder
 */
class SubscriberBroadcastResponder extends Worker
{
    private AuthenticatedUserClientCollection $userClients;

    public const FILTER_CALLBACK = true;

    /**
     * @param WsConnection $from
     * @param SubscriberBroadcastCommand $command
     * @param JsonCommandResponse $jsonData
     * @return void
     */
    private function sendToSubscribers(WsConnection $from, SubscriberBroadcastCommand $command, JsonCommandResponse $jsonData): void
    {
        foreach ($command->getSubscribers() as $subscriber) {
            foreach ($this->userClients->getClients() as $userClient) {
                if ($userClient->getUser()->getId() == $subscriber->getId()) {
                    if (self::FILTER_CALLBACK) {
                        //filter sender
                        if ($userClient->getConnection()->resourceId != $from->resourceId) {
                            $userClient->getConnection()->send($jsonData);
                        }
                    } else {
                        $userClient->getConnection()->send($jsonData);
                    }
                }
            }
        }
    }

    /**
     * @param AuthenticatedUserClientCollection $userClients
     */
    public function __construct(AuthenticatedUserClientCollection $userClients)
    {
        $this->userClients = $userClients;
    }


    /**
     * @param WsConnection $from
     * @param Command $command
     * @param JsonCommandResponse $jsonData
     * @return void
     */
    public function onMessage(WsConnection $from, Command $command, JsonCommandResponse $jsonData): void
    {

        //Respond to Subscribers 
        $this->sendToSubscribers($from, $command, $jsonData);
    }
}