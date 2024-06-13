<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\Chat;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use App\Server\JsonWebsocketServer\CommandException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * ChatLoadCommand
 */
#[AsCommand(
    name: 'chat:load',
    description: 'Load a Chat',
)]
class ChatLoadCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
            ->addArgument('page', InputArgument::OPTIONAL, 'page');
    }

    /**
     * @param InputInterface $input
     * @return string
     * @throws CommandException
     */
    public function handle(InputInterface $input): string
    {
        $user = $this->authorize($input->getArgument('token'));
        $chatId = $input->getArgument('chatId');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id' => $chatId]);
        if (!in_array($user, $chat->getUsers()->toArray())) {
            throw new CommandException('Unauthorized',401);
        }
        return $this->serializer->serialize($chat, 'json', ['groups' => ['app:chat']]);
    }
}
