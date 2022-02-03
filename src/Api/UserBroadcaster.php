<?php
namespace App\Api;
use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;
use App\Api\UserBroadcastCommand;
use App\Api\WebsocketUserClient;

class UserBroadcaster {

    private array $userClients;

    private function sendToSubscribers(WsConnection $from,UserBroadcastCommand $command,string $jsonData) {
        foreach($command->getSubscribers() as $subscriber) {
            foreach($this->userClients as $userClient) {
                if ($userClient->getUser()->getId()  == $subscriber->getId()) {
                    if ($this->userClients[$from->resourceId]->getUser()->getId()!=$subscriber->getId()) {
                        $userClient->getConnection()->send($jsonData);
                    }
                }
            }
        }
    }

    public function __construct() {
        $this->userClients = [];
    }

    public function addClient(WsConnection $client, User $user) {
        $this->userClients[$client->resourceId]=new WebsocketUserClient($client,$user);
    }

    public function push(WsConnection $from,UserBroadcastCommand $command,string $jsonData) {

        //Respond to Sender 
        $from->send($jsonData); 
        
        //Respond to Subscribers 
        $this->sendToSubscribers($from,$command,$jsonData);
    }
}