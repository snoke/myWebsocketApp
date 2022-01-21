<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Repository\ChatRepository;

use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:chat',
    description: 'Add a short description for your command',
)]
class ChatCommand extends Command
{
    public function __construct(ChatRepository $chats, SerializerInterface $serializer) {
        parent::__construct();
        $this->chats = $chats;

        $this->serializer = $serializer;
    }

protected function configure(): void
{
    $this
        ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
    ;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $chatId = $input->getArgument('chatId');
    $chat = $this->chats->findOneBy(['id'=> $chatId]);


    //$jsonContent = $this->serializer->serialize($chats, 'json', ['groups' => ['app_user_chats']]);
    $jsonContent = $this->serializer->serialize($chat, 'json', ['groups' => ['app_chat']]);

    $output->write($jsonContent);
    
    return Command::SUCCESS;
}
}
