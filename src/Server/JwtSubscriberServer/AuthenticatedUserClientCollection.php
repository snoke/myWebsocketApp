<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer;

use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Security\Core\User\UserInterface as User;

/**
 * AuthenticatedUserClientCollection
 */
class AuthenticatedUserClientCollection
{
    private array $userClients;

    public function __construct()
    {
        $this->userClients = [];
    }

    /**
     * @param WsConnection $conn
     * @param User $user
     * @return void
     */
    public function addClient(WsConnection $conn, User $user): void
    {
        $this->userClients[$conn->resourceId] = new AuthenticatedUserClient($conn, $user);
    }

    /**
     * @param WsConnection $conn
     * @return void
     */
    public function removeClient(WsConnection $conn): void
    {
        unset($this->userClients[$conn->resourceId]);
    }

    /**
     * @return array
     */
    public function getClients(): array
    {
        return $this->userClients;
    }
}