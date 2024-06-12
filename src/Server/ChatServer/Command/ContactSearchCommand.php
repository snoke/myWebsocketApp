<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\User;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 *
 */
#[AsCommand(
    name: 'contact:search',
    description: 'search user by name (like search)',
)]
class ContactSearchCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'search users');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws JWTDecodeFailureException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) {
            return 401;
        }
        $this->em->clear();
        $users = $this->em->getRepository(User::class);
        $username = $input->getArgument('username');
        $users = $users->findByLike(['username' => $username]);
        $jsonContent = $this->serializer->serialize($users, 'json', ['groups' => ['app_user_search']]);
        // $jsonContent = $this->serializer->serialize($users, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['roles','password','userIdentifier','chats']]);

        $output->write($jsonContent);

        return Command::SUCCESS;
    }
}