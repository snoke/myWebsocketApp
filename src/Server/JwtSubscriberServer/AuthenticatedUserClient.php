<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer;

use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Security\Core\User\UserInterface as User;

/**
 * AuthenticatedUserClient
 */
class AuthenticatedUserClient
{
    private WsConnection $connection;
    private User $user;

    /**
     * @param WsConnection $connection
     * @param User $user
     */
    public function __construct(WsConnection $connection, User $user)
    {
        $this->connection = $connection;
        $this->user = $user;
    }

    /**
     * @return WsConnection
     */
    public function getConnection(): WsConnection
    {
        return $this->connection;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}