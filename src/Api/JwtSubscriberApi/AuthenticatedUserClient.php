<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JwtSubscriberApi;
use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Security\Core\User\UserInterface as User;

class AuthenticatedUserClient {
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