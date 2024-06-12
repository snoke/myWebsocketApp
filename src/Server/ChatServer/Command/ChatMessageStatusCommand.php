<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

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
    name: 'chat:message:status',
    description: 'Sets a Message Status',
)]
class ChatMessageStatusCommand extends AbstractCommand
{

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('messageId', InputArgument::OPTIONAL, 'the ChatMessage ID')
            ->addArgument('status', InputArgument::OPTIONAL, "possible values are: 'sent','delivered','seen'");
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
        $status = $input->getArgument('status');
        $id = $input->getArgument('messageId');

        $message = $this->em->getRepository(ChatMessage::class)->findOneBy(['id' => $id]);
        $message->setStatus($status);
        $message->__set($status, new \DateTime());

        $this->em->persist($message);
        $this->em->flush();

        $jsonContent = $this->serializer->serialize($message, 'json', ['groups' => ['chat_message_status']]);
        $output->write($jsonContent);
        $this->setSubscribers($message->getChat()->getUsers());
        return Command::SUCCESS;

    }
}
