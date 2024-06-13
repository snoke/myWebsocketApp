<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\Chat;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use App\Server\JsonWebsocketServer\CommandException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ChatTypingCommand
 */
#[AsCommand(
    name: 'chat:typing',
    description: 'Add a short description for your command',
)]
class ChatTypingCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId');
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
            throw new CommandException('Unauthorized', 401);
        }

        $this->setSubscribers($chat->getUsers());

        return json_encode([
            'user' => ['id' => $user->getId(), 'username' => $user->getUsername()],
            'chat' => ['id' => $chat->getId()]
        ]);
    }
}
