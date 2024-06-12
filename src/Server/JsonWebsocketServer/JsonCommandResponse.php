<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JsonWebsocketServer;

use Symfony\Component\Console\Input\ArrayInput;

/**
 *
 */
class JsonCommandResponse
{
    private string $command;
    private ArrayInput $params;
    private int $statusCode;
    private string $output;

    /**
     * @param string $command
     * @param ArrayInput $params
     * @param int $statusCode
     * @param string $output
     */
    public function __construct(string $command, ArrayInput $params, int $statusCode, string $output)
    {
        $this->command = $command;
        $this->params = $params;
        $this->statusCode = $statusCode;
        $this->output = $output;
    }

    /**
     * @return bool|string
     */
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

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return ArrayInput
     */
    public function getParams(): ArrayInput
    {
        return $this->params;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->output;
    }
}