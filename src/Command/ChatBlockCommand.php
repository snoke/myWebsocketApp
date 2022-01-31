<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Chat;
#[AsCommand(
    name: 'chat:block',
    description: 'Add a short description for your command',
)]
class ChatBlockCommand extends AbstractCommand
{
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        parent::__construct($em,$serializer);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
            ->addArgument('userId', InputArgument::REQUIRED, 'userId')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chatId = $input->getArgument('chatId');
        $userId = $input->getArgument('userId');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=> $chatId]);
        if ($chat->getBlockedBy()!=null) {
            return Command::FAILURE;
        } 
        $user = $this->em->getRepository(User::class)->findOneBy(['id'=> $userId]);
        $chat->setBlockedBy($user);
        $this->em->persist($chat);
        $this->em->flush();
        $jsonContent = $this->serializer->serialize($chat, 'json', ['groups' => ['app_chat']]);
        $output->write($jsonContent);
        
        return Command::SUCCESS;
    }
}
