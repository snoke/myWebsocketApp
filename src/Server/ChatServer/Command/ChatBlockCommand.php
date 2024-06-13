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
 * ChatBlockCommand
 */
#[AsCommand(
    name: 'chat:block',
    description: 'Add a short description for your command',
)]
class ChatBlockCommand extends AbstractCommand
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
            throw new CommandException('user not found');
        }
        $chat->setBlockedBy($user);
        $this->em->persist($chat);
        $this->em->flush();
        $this->setSubscribers($chat->getUsers());
        return $this->serializer->serialize($chat, 'json', ['groups' => ['app:chat']]);
    }
}
