<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Api\ChatApi\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use App\Entity\User;
use App\Api\ChatApi\ChatCommand as AbstractCommand;

#[AsCommand(
    name: 'chat:load:userchats',
    description: 'Retrieves Chats participated by given User Id',
)]
class ChatLoadUserchatsCommand extends AbstractCommand
{
protected function configure(): void
{
    parent::configure();
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $token = $input->getArgument('token');
    $user = $this->getUserByToken($token);
    if (!$user) { return 401; }
    $this->em->clear();
    $chats = $user->getChats();
    $jsonContent = $this->serializer->serialize($chats, 'json', ['groups' => ['app_user_chats']]);

    $output->write($jsonContent);
    
    return Command::SUCCESS;
}
}
