<?php

namespace App\Server\JsonWebsocketServer;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * JsonCommand
 */
abstract class JsonCommand extends Command
{
    /**
     * @param InputInterface $input
     * @return string
     */
    abstract public function handle(InputInterface $input): string;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $result = $this->handle($input);
        } catch(CommandException $exception) {
            $output->write($exception->getMessage());
            return Command::FAILURE;
        }
        $output->write($result);
        return Command::SUCCESS;
    }
}