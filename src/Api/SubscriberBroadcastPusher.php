<?php
namespace App\Api;
use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;
use App\Api\SubscriberBroadcastCommand;
use App\Api\AuthenticatedUserClient;
use App\Api\JsonCommandResponse;
use App\Api\WorkerInterface;

class SubscriberBroadcastPusher implements WorkerInterface {
    private AuthenticatedUserClientCollection $userClients;

    const ALWAYS_RESPOND_TO_SENDER = true;


    private function sendToSubscribers(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {
        foreach($command->getSubscribers() as $subscriber) {
            foreach($this->userClients->getClients() as $userClient) {
                if ($userClient->getUser()->getId()  == $subscriber->getId()) {
                    //filter sender
                    if ($userClient->getConnection()->resourceId!=$from->resourceId) {
                        $userClient->getConnection()->send($jsonData);
                    }
                }
            }
        }
    }

    public function __construct(AuthenticatedUserClientCollection $userClients) {
        $this->userClients = $userClients;
    }


    public function work(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {

        //Respond to Subscribers 
        $this->sendToSubscribers($from,$command,$jsonData);
    }
}