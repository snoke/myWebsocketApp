<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\JsonApi;

use \Ratchet\WebSocket\WsConnection;
use Symfony\Component\Console\Command\Command;

/**
 * this class provides call-in hooks for api modules
 * these methods require existence to be called, although they need not to have any body
 */
abstract class AbstractWorker
{
    public function onMessage(WsConnection $from, Command $command, JsonCommandResponse $response): void
    {
        return;
    }

    public function onClose(WsConnection $from): void
    {
        return;
    }

    public function onOpen(WsConnection $from): void
    {
        return;
    }
}