<?php
namespace App\Api;
use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Security\Core\User\UserInterface as User;

class WebsocketUserClient {
    private WsConnection $connection;
    private User $user;

    public function __construct(WsConnection $connection,User $user) {
        $this->connection = $connection;
        $this->user = $user;
    }
    public function getConnection():WsConnection {
        return $this->connection;
    }
    public function getUser():User {
        return $this->user;
    }
}