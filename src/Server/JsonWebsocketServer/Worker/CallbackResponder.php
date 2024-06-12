<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JsonWebsocketServer\Worker;

use App\Server\JsonWebsocketServer\AbstractWorker as Worker;
use App\Server\JsonWebsocketServer\JsonCommandResponse;
use Ratchet\WebSocket\WsConnection;
use Symfony\Component\Console\Command\Command;

/**
 *
 */
Class CallbackResponder extends Worker
{

    /**
     * @param WsConnection $from
     * @param Command $command
     * @param JsonCommandResponse $jsonData
     * @return void
     */
    public function onMessage(WsConnection $from, Command $command, JsonCommandResponse $jsonData): void
    {
        $from->send($jsonData);
    }
}