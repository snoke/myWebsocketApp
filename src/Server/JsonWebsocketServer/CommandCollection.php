<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JsonWebsocketServer;


use Symfony\Component\Console\Command\Command;

/**
 * CommandCollection
 */
class CommandCollection
{
    private array $commands;

    /**
     * @param Command $command
     * @return void
     */
    protected function addCommand(Command $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * @param string $action
     * @return Command
     */
    public function find(string $action): Command
    {
        foreach ($this->commands as $command) {
            if ($command->getName() == $action) {
                return $command;
            }
        }
    }

    public function __construct()
    {

        $this->commands = [];
    }
}