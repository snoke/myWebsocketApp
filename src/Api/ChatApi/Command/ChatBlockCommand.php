<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */
namespace App\Api\ChatApi\Command;

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
use App\Api\ChatApi\ChatCommand as AbstractCommand;
#[AsCommand(
    name: 'chat:block',
    description: 'Add a short description for your command',
)]
class ChatBlockCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) { return 401; }
        $chatId = $input->getArgument('chatId');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=> $chatId]);
        if (!in_array($user,$chat->getUsers()->toArray())) { return 401; }
        if ($chat->getBlockedBy()!=null) {
            return Command::FAILURE;
        } 
        $chat->setBlockedBy($user);
        $this->em->persist($chat);
        $this->em->flush();
        $jsonContent = $this->serializer->serialize($chat, 'json', ['groups' => ['app_chat']]);
        $output->write($jsonContent);
        
        $this->setSubscribers($chat->getUsers());
        return Command::SUCCESS;
    }
}
