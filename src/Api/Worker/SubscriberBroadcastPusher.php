<?php
namespace App\Api\Worker;
use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;
use App\Api\SubscriberBroadcastCommand;
use App\Api\AuthenticatedUserClient;
use App\Api\JsonCommandResponse;
use App\Api\AuthenticatedUserClientCollection;
use App\Api\AbstractWorker as Worker;

class SubscriberBroadcastPusher extends Worker {
    private AuthenticatedUserClientCollection $userClients;

    const CALLBACK = false;

    private function sendToSubscribers(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {
        foreach($command->getSubscribers() as $subscriber) {
            foreach($this->userClients->getClients() as $userClient) {
                if ($userClient->getUser()->getId()  == $subscriber->getId()) {
                    if (self::CALLBACK) {
                        $userClient->getConnection()->send($jsonData);
                    } else {
                        //filter sender
                        if ($userClient->getConnection()->resourceId!=$from->resourceId) {
                            $userClient->getConnection()->send($jsonData);
                        }
                    }
                }
            }
        }
    }

    public function __construct(AuthenticatedUserClientCollection $userClients) {
        $this->userClients = $userClients;
    }


    public function onMessage(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {

        //Respond to Subscribers 
        $this->sendToSubscribers($from,$command,$jsonData);
    }
}