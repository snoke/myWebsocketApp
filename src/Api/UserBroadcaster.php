<?php
namespace App\Api;
use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;
use App\Api\UserBroadcastCommand;

class UserBroadcaster {

    private array $userClients;

    private function send(WsConnection $from,UserBroadcastCommand $command,array $body) {
        $subscribers = $command->getSubscribers();
        foreach($subscribers as $subscriber) {
            foreach($this->userClients as $userClient) {
                if ($userClient['user']->getId()  == $subscriber->getId()) {
                    if ($this->userClients[$from->resourceId]["user"]->getId()!=$subscriber->getId()) {
                        $userClient['connection']->send(json_encode($body));
                    }
                }
            }
        }
    }

    public function __construct() {
        $this->userClients = [];
    }

    public function addClient(WsConnection $client, User $user) {
        $this->userClients[$client->resourceId]=[
            "connection" => $client,
            "user"  => $user,
        ];
    }
    public function push(WsConnection $from,UserBroadcastCommand $command,array $body) {
        //Respond to Sender 
        $from->send(json_encode($body)); 
        //Respond to Subscribers 
        $this->send($from,$command,$body);
    }
}