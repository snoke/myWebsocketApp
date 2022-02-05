<?php
namespace App\Api;
use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;
use App\Api\SubscriberBroadcastCommand;
use App\Api\AuthenticatedUserClient;
use App\Api\JsonCommandResponse;

class AuthenticatedUserClientCollection {
    private array $userClients;

    public function __construct() {
        $this->userClients = [];
    }

    public function addClient(WsConnection $client, User $user) {
        $this->userClients[$client->resourceId]=new AuthenticatedUserClient($client,$user);
    }
    public function removeClient(WsConnection $clien) {
        unset($this->userClients[$client->resourceId]);
    }
    public function getClients() {
       return $this->userClients;
    }
}