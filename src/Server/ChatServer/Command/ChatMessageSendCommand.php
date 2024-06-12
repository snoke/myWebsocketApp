<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\File;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 *
 */
#[AsCommand(
    name: 'chat:message:send',
    description: 'Sends a chat message',
)]
class ChatMessageSendCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('message', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('file', InputArgument::OPTIONAL, 'Argument description');
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
        $io = new SymfonyStyle($input, $output);
        $chatMessage = new ChatMessage();
        $chatMessage->setMessage($input->getArgument('message'));
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id' => $input->getArgument('chatId')]);
        if (!in_array($user, $chat->getUsers()->toArray())) {
            return 401;
        }
        if ($chat->getBlockedBy() != null) {
            return Command::FAILURE;
        }
        $chatMessage->setSender($user);
        $chatMessage->setChat($chat);
        $chatMessage->setFile($this->em->getRepository(File::class)->findOneBy(['id' => $input->getArgument('file')]));
        $chatMessage->setSent(new \DateTime());
        $chatMessage->setStatus('sent');
        $this->em->persist($chatMessage);
        $this->em->flush();
        $jsonContent = $this->serializer->serialize($chatMessage, 'json', ['groups' => ['app_chat_send']]);
        $output->write($jsonContent);
        $this->setSubscribers($chat->getUsers());
        return Command::SUCCESS;
    }
}
