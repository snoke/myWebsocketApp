<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi;
use \Ratchet\WebSocket\WsConnection;
use App\Api\JsonApi\JsonCommandResponse;
use Symfony\Component\Console\Command\Command;

/**
 * this class provides call-in hooks for api modules
 * these methods require existence to be called, although they need not to have any body
 */
abstract class AbstractWorker {
      public function onMessage(WsConnection $from,Command $command,JsonCommandResponse $response) { return; }
      public function onClose(WsConnection $from) { return; }
      public function onOpen(WsConnection $from) { return; }
};