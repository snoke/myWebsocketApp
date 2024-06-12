<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer\Command;

use App\Entity\User;
use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand as AbstractCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

//use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface as User;
//use Symfony\Component\Security\Core\User\UserInterface as User;

/**
 *
 */
#[AsCommand(
    name: 'auth:register',
    description: 'Sign up a new User',
)]
class AuthRegisterCommand extends AbstractCommand
{
    private UserPasswordHasherInterface $passwordHasher;
    private SerializerInterface $serializer;
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, UserPasswordHasherInterface $userPasswordHasher)
    {

        parent::__construct();

        $this->passwordHasher = $userPasswordHasher;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('loginName', InputArgument::REQUIRED, 'username')
            ->addArgument('password', InputArgument::REQUIRED, 'password')
            ->addArgument('password2', InputArgument::REQUIRED, ' password2');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loginName = $input->getArgument('loginName');
        $password = $input->getArgument('password');
        $password2 = $input->getArgument('password2');


        if ($password !== $password2) {
            $output->write('passwords are not identical');
            return Command::FAILURE;
        }
        if (strlen($password) < 4) {
            $output->write('password is too short');
            return Command::FAILURE;
        }
        if ($this->em->getRepository(User::class)->findOneBy(['username' => $loginName])) {
            $output->write('username already taken');
            return Command::FAILURE;
        }
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
        $output->write($user->getId());
        return Command::SUCCESS;
    }
}
