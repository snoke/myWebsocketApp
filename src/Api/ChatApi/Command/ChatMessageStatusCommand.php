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

use App\Entity\ChatMessage;

use App\Api\ChatApi\ChatCommand as AbstractCommand;
#[AsCommand(
    name: 'chat:message:status',
    description: "Sets a Message Status",
)]
class ChatMessageStatusCommand extends AbstractCommand
{
    
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('messageId', InputArgument::OPTIONAL, "the ChatMessage ID")
            ->addArgument('status', InputArgument::OPTIONAL, "possible values are: 'sent','delivered','seen'")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) { return 401; }
        $status = $input->getArgument('status');
        $id = $input->getArgument('messageId');

        $message= $this->em->getRepository(ChatMessage::class)->findOneBy(['id'=>$id]);
        $message->setStatus($status);
        $message->__set($status,new \DateTime());
        
        $this->em->persist($message);
        $this->em->flush();

        $jsonContent = $this->serializer->serialize($message, 'json', ['groups' => ['chat_message_status']]);
        $output->write($jsonContent);
        $this->setSubscribers($message->getChat()->getUsers());
        return Command::SUCCESS;

    }
}
