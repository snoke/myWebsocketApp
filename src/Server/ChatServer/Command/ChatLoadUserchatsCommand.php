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
 * ChatLoadUserchatsCommand
 */
#[AsCommand(
    name: 'chat:load:userchats',
    description: 'Retrieves Chats participated by given User Id',
)]
class ChatLoadUserchatsCommand extends AbstractCommand
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
        $chats = $user->getChats();
        return $this->serializer->serialize($chats, 'json', ['groups' => ['app:user:chats']]);
    }
}
