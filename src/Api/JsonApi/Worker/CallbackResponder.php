<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi\Worker;
use Ratchet\WebSocket\WsConnection;
use App\Api\JsonApi\JsonCommandResponse;
use Symfony\Component\Console\Command\Command;

use App\Api\JsonApi\AbstractWorker as Worker;

 Class CallbackResponder extends Worker {

    public function onMessage(WsConnection $from,Command $command,JsonCommandResponse $jsonData) {
        $from->send($jsonData); 
    }
}