<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\JsonApi;


use Symfony\Component\Console\Command\Command;

class CommandCollection {
    private array $commands;
    
    protected function addCommand(Command $command) {
        $this->commands[] = $command;
    }
    public function find(string $action):Command {
        foreach($this->commands as $command) {
            if ($command->getName()==$action) {
                return $command;
            }
        }
    }
    public function __construct() {

        $this->commands=[];
    }
}