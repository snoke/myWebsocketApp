<?php

namespace App\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\File;
use App\Entity\User;

#[AsCommand(
    name: 'chat:message:send',
    description: 'Sends a chat message',
)]
class ChatMessageSendCommand extends AbstractCommand
{
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        parent::__construct($em,$serializer);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('senderId', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('chatId', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('message', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('file', InputArgument::OPTIONAL, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $chatMessage = new ChatMessage();
        $chatMessage->setMessage($input->getArgument('message'));
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=>$input->getArgument('chatId')]);
        if ($chat->getBlockedBy()!=null) {
            return Command::FAILURE;
        }
        $chatMessage->setSender($this->em->getRepository(User::class)->findOneBy(['id'=>$input->getArgument('senderId')]));
        $chatMessage->setChat($chat);
        $chatMessage->setFile($this->em->getRepository(File::class)->findOneBy(['id'=>$input->getArgument('file')]));
        $chatMessage->setSent(new \DateTime());
        $chatMessage->setStatus('sent');
        $this->em->persist($chatMessage);
        $this->em->flush();
        $jsonContent = $this->serializer->serialize($chatMessage, 'json', ['groups' => ['app_chat_send']]);
        $output->write($jsonContent);
        return Command::SUCCESS;
    }
}
