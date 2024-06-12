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
Class BroadcastResponder extends Worker
{
    private array $connections;

    /**
     * @param WsConnection $from
     * @return void
     */
    public function onOpen(WsConnection $from): void
    {
        $this->connections[$from->resourceId] = $from;
    }

    /**
     * @param WsConnection $from
     * @return void
     */
    public function onClose(WsConnection $from): void
    {
        unset($this->connections[$from->resourceId]);
    }

    /**
     * @param WsConnection $from
     * @param Command $command
     * @param JsonCommandResponse $jsonData
     * @return void
     */
    public function onMessage(WsConnection $from, Command $command, JsonCommandResponse $jsonData): void
    {
        foreach ($this->connections as $connection) {
            $connection->send($jsonData);
        }
    }
}