<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi\Worker;
use Ratchet\WebSocket\WsConnection;
use App\Api\JsonApi\JsonCommandResponse;
use Symfony\Component\Console\Command\Command;

use App\Api\JsonApi\AbstractWorker as Worker;

 Class BroadcastResponder extends Worker {
    private array $connections;

    public function onOpen(WsConnection $from) {
        $this->connections[$from->resourceId] = $from;
    }
    public function onClose(WsConnection $from) {
        unset($this->connections[$from->resourceId]);
    }
    public function onMessage(WsConnection $from,Command $command,JsonCommandResponse $jsonData) {
        foreach($this->connections as $connection) {
            $connection->send($jsonData); 
        }
    }
}