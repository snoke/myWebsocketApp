<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi;
use Symfony\Component\Console\Input\ArrayInput;
class JsonCommandResponse {
    public function __construct(string $command,ArrayInput $params, int $statusCode,string $output) {
        $this->command = $command;
        $this->params = $params;
        $this->statusCode = $statusCode;
        $this->output = $output;
    }
    private function encode() {
        return json_encode([
            "command"=>$this->command,
            "params"=>$this->params,
            "status"=>$this->statusCode,
            "data"=>$this->output,
        ]);
    }
    public function __toString() {
        return $this->encode();
    }
    public function getCommand() {
        return $this->command;
    }
    public function getParams() {
        return $this->params;
    }
    public function getStatusCode() {
        return $this->statusCode;
    }
    public function getData() {
        return $this->output;
    }
}