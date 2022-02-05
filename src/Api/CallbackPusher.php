<?php
namespace App\Api;
use Ratchet\WebSocket\WsConnection;
use App\Api\SubscriberBroadcastCommand;
use App\Api\JsonCommandResponse;

use App\Api\WorkerInterface;

 Class CallbackPusher implements WorkerInterface {

    public function work(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $jsonData) {

            $from->send($jsonData); 
        
    }
}