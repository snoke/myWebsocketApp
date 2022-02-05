<?php
namespace App\Api\Worker;
use Ratchet\WebSocket\WsConnection;
use App\Api\SubscriberBroadcastCommand;
use App\Api\JsonCommandResponse;

use App\Api\AbstractWorker as Worker;

 Class CallbackPusher extends Worker {

    public function onMessage(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {
        $from->send($jsonData); 
    }
}