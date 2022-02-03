<?php
namespace App\Api;
class JsonResponse {
    public function __construct(string $command,array $params, int $statusCode,string $data) {
        $this->command = $command;
        $this->params = $params;
        $this->statusCode = $statusCode;
        $this->data = $data;
    }
    private function encode() {
        return json_encode([
            "command"=>$this->command,
            "params"=>$this->params,
            "status"=>$this->statusCode,
            "data"=>$this->data,
        ]);
    }
    public function __toString() {
        return $this->encode();
    }
}