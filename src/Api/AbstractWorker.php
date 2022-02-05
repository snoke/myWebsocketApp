<?php
namespace App\Api;
use \Ratchet\WebSocket\WsConnection;
use App\Api\JsonCommandResponse;
use App\Api\SubscriberBroadcastCommand;

/**
 * this class provides calling hooks
 * these methods require existence to be called, although they need not to have any body
 */
abstract class AbstractWorker {
      public function onMessage(WsConnection $from,SubscriberBroadcastCommand $command,JsonCommandResponse $response) { return; }
      public function onClose(WsConnection $from) { return; }
      public function onOpen(WsConnection $from) { return; }
};