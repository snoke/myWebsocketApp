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

#[AsCommand(
    name: 'app:user:search',
    description: 'Add a short description for your command',
)]
class UserSearchCommand extends Command
{  
    public function __construct(UserRepository $users) {
    parent::__construct();
    $this->users = $users;

    $this->serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
}

protected function configure(): void
{
    $this
        ->addArgument('username', InputArgument::REQUIRED, 'search users')
    ;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $username = $input->getArgument('username');
    $users = $this->users->findByLike(['username'=> $username]);
    $jsonContent = $this->serializer->serialize($users, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles','password','userIdentifier','chats']]);

    $output->write($jsonContent);
    
    return Command::SUCCESS;
}
}
