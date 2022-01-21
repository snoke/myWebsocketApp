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

use App\Repository\UserRepository;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Entity\Chat;


use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:contact:add',
    description: 'Add a short description for your command',
)]
class ContactAddCommand extends Command
{
    public function __construct(UserRepository $users,EntityManagerInterface $em, SerializerInterface $serializer) {
        parent::__construct();
        $this->serializer = $serializer;
        $this->users = $users;
        $this->em = $em;

    }

    protected function configure(): void
    {
        $this
            ->addArgument('alice', InputArgument::REQUIRED, 'id of alice')
            ->addArgument('bob', InputArgument::REQUIRED, 'id of bob')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $alice = $this->users->findOneBy(['id'=>$input->getArgument('alice')]);
        $bob = $this->users->findOneBy(['id'=>$input->getArgument('bob')]);
        $alice->addContact($bob); 
        $bob->addContact($alice); 
        $chat = new Chat();
        $chat->addUser($alice);
        $chat->addUser($bob);
        $this->em->persist($alice);
        $this->em->persist($bob);
        $this->em->persist($chat);
        $this->em->flush();
        $output->write($chat->getId());
        return Command::SUCCESS;
    }
}
