<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JsonWebsocketServer;

use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Console\Command\Command;

/**
 * this class provides call-in hooks for server modules
 * these methods require existence to be called, although they need not to have any body
 */
abstract class AbstractWorker
{
    /**
     * @param WsConnection $from
     * @param Command $command
     * @param JsonCommandResponse $response
     * @return void
     */
    public function onMessage(WsConnection $from, Command $command, JsonCommandResponse $response): void
    {
    }

    /**
     * @param WsConnection $from
     * @return void
     */
    public function onClose(WsConnection $from): void
    {
    }

    /**
     * @param WsConnection $from
     * @return void
     */
    public function onOpen(WsConnection $from): void
    {
    }
}