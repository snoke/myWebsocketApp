<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Server\ChatServer\ChatCommand as AbstractCommand;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 *
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
        $contacts = $user->getContacts();
        $jsonContent = $this->serializer->serialize($contacts, 'json', ['groups' => ['app_user_contacts']]);

        $output->write($jsonContent);

        return Command::SUCCESS;
    }
}
