<?php

namespace App\Websocket\JsonApi\Command;

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
use App\Websocket\WebsocketCommand as AbstractCommand;
#[AsCommand(
    name: 'chat:typing',
    description: 'Add a short description for your command',
)]
class ChatTypingCommand extends AbstractCommand
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
            ->addArgument('userId', InputArgument::REQUIRED, 'userId')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chatId = $input->getArgument('chatId');
        $userId = $input->getArgument('userId');
        $user = $this->em->getRepository(User::class)->findOneBy(['id'=>$userId]);
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=>$chatId]);
        $output->write(json_encode([
            'user' => ['id'=>$user->getId(),'username'=>$user->getUsername()],
            'chat' => ['id' => $chat->getId()]
        ]));
        
        $this->setSubscribers($chat->getUsers());
            
        return Command::SUCCESS;
    }
}
