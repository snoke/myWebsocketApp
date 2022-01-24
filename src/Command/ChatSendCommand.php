<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;

use App\Repository\ChatRepository;
use App\Repository\UserRepository;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Entity\Chat;
use App\Entity\ChatMessage;


use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:chat:send',
    description: 'Sends a chat message',
)]
class ChatSendCommand extends Command
{
    public function __construct(UserRepository $users,ChatRepository $chats,EntityManagerInterface $em, SerializerInterface $serializer) {
        parent::__construct();
        $this->chats = $chats;
        $this->users = $users;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('senderId', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('chatId', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('message', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $chatMessage = new ChatMessage();
        $chatMessage->setMessage($input->getArgument('message'));
        $chatMessage->setSender($this->users->findOneBy(['id'=>$input->getArgument('senderId')]));
        $chatMessage->setChat($this->chats->findOneBy(['id'=>$input->getArgument('chatId')]));
        $this->em->persist($chatMessage);
        $this->em->flush();
        $jsonContent = $this->serializer->serialize($chatMessage, 'json', ['groups' => ['app_chat_send']]);
        $output->write($jsonContent);
        return Command::SUCCESS;
    }
}
