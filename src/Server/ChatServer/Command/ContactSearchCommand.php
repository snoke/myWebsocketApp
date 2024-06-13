<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\User;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use App\Server\JsonWebsocketServer\CommandException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * ContactSearchCommand
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
     * @return string
     * @throws CommandException
     */
    public function handle(InputInterface $input): string
    {
        $user = $this->authorize($input->getArgument('token'));
        $this->em->clear();
        $users = $this->em->getRepository(User::class);
        $username = $input->getArgument('username');
        $users = $users->findByLike(['username' => $username]);
        return $this->serializer->serialize($users, 'json', ['groups' => ['app:user:search']]);
    }
}