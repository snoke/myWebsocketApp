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
use App\Repository\UserRepository;

use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:user:chats',
    description: 'Retrieves Chats participated by given User Id',
)]
class UserChatsCommand extends Command
{
    public function __construct(UserRepository $users, SerializerInterface $serializer) {
    parent::__construct();
    $this->users = $users;
    $this->serializer = $serializer;

   // $this->serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
}

protected function configure(): void
{
    $this
        ->addArgument('userid', InputArgument::REQUIRED, 'userid')
    ;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $userid = $input->getArgument('userid');
    $user = $this->users->findOneBy(['id'=> $userid]);
    $chats = $user->getChats();
    //$jsonContent = $this->serializer->serialize($chats, 'json', [AbstractNormalizer::ATTRIBUTES  => ['id']]);
    $jsonContent = $this->serializer->serialize($chats, 'json', ['groups' => ['app_user_chats']]);

    $output->write($jsonContent);
    
    return Command::SUCCESS;
}
}
