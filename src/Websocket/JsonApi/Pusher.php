<?php
namespace App\Websocket\JsonApi;
use App\Entity\User;
use Ratchet\WebSocket\WsConnection;
use App\Websocket\WebsocketCommand;

class Pusher {
    private $clients;

    public function __construct() {
        $this->userClients = [];
    }
    public function addClient($client,User $user) {
        $this->userClients[$client->resourceId]=[
            "connection" => $client,
            "user"  => $user,
            "ip"  => $client->remoteAddress,
        ];

    }
    private function send($from,$body,$subscribers) {
        foreach($subscribers as $subscriber) {
            foreach($this->userClients as $userClient) {
                if ($userClient['user']->getId()  == $subscriber->getId()) {
                    if ($from["user"]->getId()!=$subscriber->getId()) {
                        $userClient['connection']->send(json_encode($body));
                    }
                }
            }
        }
    }
    public function push(WsConnection $from,WebsocketCommand $command,$body) {

        //Respond to Sender 
        $from->send(json_encode($body)); 
        //Respond to Subscribers 
        $this->send($this->userClients[$from->resourceId],$body,$command->getSubscribers());
    }
}