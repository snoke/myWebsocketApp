<?php
namespace App\Api;
use \Ratchet\WebSocket\WsConnection;
use App\Api\JsonCommandResponse;
interface WorkerInterface {
    public function work(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData);
};