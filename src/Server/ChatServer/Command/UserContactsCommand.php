<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Server\ChatServer\ChatCommand as AbstractCommand;
use App\Server\JsonWebsocketServer\CommandException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * UserContactsCommand
 */
#[AsCommand(
    name: 'user:contacts',
    description: 'gets user contacts',
)]
class UserContactsCommand extends AbstractCommand
{

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
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
        $contacts = $user->getContacts();
        return $this->serializer->serialize($contacts, 'json', ['groups' => ['app:user:contacts']]);
    }
}
