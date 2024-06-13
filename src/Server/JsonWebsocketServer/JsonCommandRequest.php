<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JsonWebsocketServer;

use Ratchet\ConnectionInterface;

/**
 * JsonCommandRequest
 */
class JsonCommandRequest
{
    private ConnectionInterface $client;
    private mixed $action;
    private mixed $params;

    /**
     * @param ConnectionInterface $client
     * @param $json
     */
    public function __construct(ConnectionInterface $client, $json)
    {
        $this->client = $client;
        $data = json_decode($json, true);
        $this->action = $data['action'];
        $this->params = $data['params'];
    }

    /**
     * @return mixed
     */
    public function getAction(): mixed
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getParams(): mixed
    {
        return $this->params;
    }
}