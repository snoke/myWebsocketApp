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
use App\Entity\User;

use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:user:chats',
    description: 'Retrieves Chats participated by given User Id',
)]
class UserChatsCommand extends Command
{
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer) {
    parent::__construct();
    $this->serializer = $serializer;
    $this->em = $em;

   // $this->serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
}

protected function configure(): void
{
    $this
        ->addArgument('userId', InputArgument::REQUIRED, 'userId')
    ;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $this->em->clear();
    $users = $this->em->getRepository(User::class);
    $userid = $input->getArgument('userId');
    $user = $users->findOneBy(['id'=> $userid]);
    $chats = $user->getChats();
    $jsonContent = $this->serializer->serialize($chats, 'json', ['groups' => ['app_user_chats']]);

    $output->write($jsonContent);
    
    return Command::SUCCESS;
}
}
