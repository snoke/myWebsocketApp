<?php

namespace App\Websocket\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\User;


use App\Websocket\WebsocketCommand as AbstractCommand;
#[AsCommand(
    name: 'contact:search',
    description: 'search user by name (like search)',
)]
class ContactSearchCommand extends AbstractCommand
{  

    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        
        parent::__construct();
        
        $this->em = $em;
        $this->serializer = $serializer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'search users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->em->clear();
        $users = $this->em->getRepository(User::class);
        $username = $input->getArgument('username');
        $users = $users->findByLike(['username'=> $username]);
        $jsonContent = $this->serializer->serialize($users, 'json', ['groups' => ['app_user_search']]);
    // $jsonContent = $this->serializer->serialize($users, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles','password','userIdentifier','chats']]);

        $output->write($jsonContent);
        
        return Command::SUCCESS;
    }
}