<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use App\Server\JsonWebsocketServer\CommandException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ChatLoadMessagesCommand
 */
#[AsCommand(
    name: 'chat:load:messages',
    description: 'Loads Chat Messages',
)]
class ChatLoadMessagesCommand extends AbstractCommand
{
    public const LIMIT = 1;

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
            ->addArgument('page', InputArgument::OPTIONAL, 'page')
            ->addArgument('steps', InputArgument::OPTIONAL, 'steps');
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
        $steps = $input->getArgument('steps');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id' => $chatId]);
        $page = $input->getArgument('page');
        $messages = $this->em->getRepository(ChatMessage::class)->findBy(['chat' => $chat], ['id' => 'desc'], $steps, $page);

        return $this->serializer->serialize($messages, 'json', ['groups' => [$this->getName()]]);
    }
}
