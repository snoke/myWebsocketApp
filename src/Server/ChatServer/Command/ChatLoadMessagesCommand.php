<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
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
     * @param OutputInterface $output
     * @return int
     * @throws JWTDecodeFailureException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) {
            return 401;
        }
        $chatId = $input->getArgument('chatId');
        $steps = $input->getArgument('steps');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id' => $chatId]);
        if (!in_array($user, $chat->getUsers()->toArray())) {
            return 401;
        }
        $page = $input->getArgument('page');
        $messages = $this->em->getRepository(ChatMessage::class)->findBy(['chat' => $chat], ['id' => 'desc'], $steps, $page);

        $jsonContent = $this->serializer->serialize($messages, 'json', ['groups' => [$this->getName()]]);
        $output->write($jsonContent);
        return Command::SUCCESS;
    }
}
