<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi;
class JsonCommandRequest {
    private $client;
    private $action;
    private $params;
    public function __construct($client,$json) {
        $this->client = $client;
        $data = json_decode($json, true); 
        $this->action = $data['action'];
        $this->params = $data['params'];
    }   
    public function getAction() {
        return $this->action;
    }
    public function getParams() {
        return $this->params;
    }
}