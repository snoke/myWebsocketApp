<?php

namespace App\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;

#[AsCommand(
    name: 'auth:register',
    description: 'Sign up a new User',
)]
class AuthRegisterCommand extends AbstractCommand
{    
    private $passwordHasher;

    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer,UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($em,$serializer);
        $this->passwordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('loginName', InputArgument::REQUIRED, 'login username')
            ->addArgument('password', InputArgument::REQUIRED, 'login password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loginName = $input->getArgument('loginName');
        $password = $input->getArgument('password');
        $user = new User();
        $user->setUsername($loginName);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $password
            )
        );
        $this->em->persist($user);
        $this->em->flush();
        $io = new SymfonyStyle($input, $output);
        $output->write($user->getId());
        return Command::SUCCESS;
    }
}
