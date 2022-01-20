<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\UserRepository;
use App\Entity\ChatRoom;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[AsCommand(
    name: 'app:chat:start',
    description: 'Add a short description for your command',
)]
class ChatStartCommand extends Command
{
    public function __construct(UserRepository $users,EntityManagerInterface $em) {
        parent::__construct();
        $this->serializer = new Serializer([new ObjectNormalizer()],  [new JsonEncoder()]);
        $this->users = $users;
        $this->em = $em;

    }

    protected function configure(): void
    {
        $this
            ->addArgument('alice', InputArgument::REQUIRED, 'id of creator')
            ->addArgument('bob', InputArgument::REQUIRED, 'id of creator')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chat = new ChatRoom();
        $chat->addParticipant($this->users->findOneBy(['id'=> $input->getArgument('alice')]));
        $chat->addParticipant($this->users->findOneBy(['id'=> $input->getArgument('bob')]));
        $this->em->persist($chat);
        $this->em->flush($chat);
        $output->write($chat->getId());
        
        return Command::SUCCESS;
    }
}
