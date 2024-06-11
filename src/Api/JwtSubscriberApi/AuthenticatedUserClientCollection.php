<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\JwtSubscriberApi;

use Symfony\Component\Security\Core\User\UserInterface as User;
use Ratchet\WebSocket\WsConnection;

/**
 *
 */
class AuthenticatedUserClientCollection
{
    private array $userClients;

    public function __construct()
    {
        $this->userClients = [];
    }

    public function addClient(WsConnection $conn, User $user): void
    {
        $this->userClients[$conn->resourceId] = new AuthenticatedUserClient($conn, $user);
    }

    public function removeClient(WsConnection $conn): void
    {
        unset($this->userClients[$conn->resourceId]);
    }

    public function getClients(): array
    {
        return $this->userClients;
    }
}