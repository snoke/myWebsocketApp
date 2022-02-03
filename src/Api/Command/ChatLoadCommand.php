<?php

namespace App\Api\Command;

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
use App\Api\UserBroadcastCommand as AbstractCommand;

#[AsCommand(
    name: 'chat:load',
    description: 'Load a Chat',
)]
class ChatLoadCommand extends AbstractCommand
{
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        
        parent::__construct();
        
        $this->em = $em;
        $this->serializer = $serializer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
            ->addArgument('page', InputArgument::OPTIONAL, 'page')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chatId = $input->getArgument('chatId');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=> $chatId]);
        $jsonContent = $this->serializer->serialize($chat, 'json', ['groups' => ['app_chat']]);
        $output->write($jsonContent);
        
        return Command::SUCCESS;
    }
}
