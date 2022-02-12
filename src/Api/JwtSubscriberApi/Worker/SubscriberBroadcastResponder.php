<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi\Worker;
use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;
use App\Api\JwtSubscriberApi\SubscriberBroadcastCommand;
use App\Api\JwtSubscriberApi\AuthenticatedUserClient;
use App\Api\JsonApi\JsonCommandResponse;
use App\Api\JwtSubscriberApi\AuthenticatedUserClientCollection;
use App\Api\JsonApi\AbstractWorker as Worker;
use Symfony\Component\Console\Command\Command;

class SubscriberBroadcastResponder extends Worker {
    private AuthenticatedUserClientCollection $userClients;

    const FILTER_CALLBACK = true; 

    private function sendToSubscribers(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {
        foreach($command->getSubscribers() as $subscriber) {
            foreach($this->userClients->getClients() as $userClient) {
                if ($userClient->getUser()->getId()  == $subscriber->getId()) {       
                    if (self::FILTER_CALLBACK) {
                        //filter sender
                        if ($userClient->getConnection()->resourceId!=$from->resourceId) {
                            $userClient->getConnection()->send($jsonData);
                        }
                    } else {
                        $userClient->getConnection()->send($jsonData);
                    }
                }
            }
        }
    }

    public function __construct(AuthenticatedUserClientCollection $userClients) {
        $this->userClients = $userClients;
    }


    public function onMessage(WsConnection $from,Command $command,JsonCommandResponse $jsonData) {

        //Respond to Subscribers 
        $this->sendToSubscribers($from,$command,$jsonData);
    }
}