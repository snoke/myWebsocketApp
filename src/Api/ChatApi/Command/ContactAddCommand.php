<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\ChatApi\Command;

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
use App\Entity\User;

use App\Api\ChatApi\ChatCommand as AbstractCommand;
#[AsCommand(
    name: 'contact:add',
    description: 'Adds a Contact',
)]
class ContactAddCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('bob', InputArgument::REQUIRED, 'id of bob')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) { return 401; }
        $users = $this->em->getRepository(User::class);
        $alice = $user;
        $bob = $users->findOneBy(['id'=>$input->getArgument('bob')]);
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
