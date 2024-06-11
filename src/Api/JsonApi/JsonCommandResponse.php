<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\JsonApi;

use Symfony\Component\Console\Input\ArrayInput;

/**
 *
 */
class JsonCommandResponse
{
    public function __construct(string $command, ArrayInput $params, int $statusCode, string $output)
    {
        $this->command = $command;
        $this->params = $params;
        $this->statusCode = $statusCode;
        $this->output = $output;
    }

    private function encode(): bool|string
    {
        return json_encode([
            'command' => $this->command,
            'params' => $this->params,
            'status' => $this->statusCode,
            'data' => $this->output,
        ]);
    }

    /**
     * @return bool|string
     */
    public function __toString()
    {
        return $this->encode();
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getParams(): ArrayInput
    {
        return $this->params;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): string
    {
        return $this->output;
    }
}