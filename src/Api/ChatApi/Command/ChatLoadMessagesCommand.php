<?php

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

use Doctrine\Common\Collections\Criteria;
use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Api\JwtSubscriberApi\SubscriberBroadcastCommand as AbstractCommand;

#[AsCommand(
    name: 'chat:load:messages',
    description: 'Loads Chat Messages',
)]
class ChatLoadMessagesCommand extends AbstractCommand
{
    CONST LIMIT = 1;

    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        
        parent::__construct();
        
        $this->em = $em;
        $this->serializer = $serializer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'chatId')
            ->addArgument('page', InputArgument::OPTIONAL, 'page')
            ->addArgument('steps', InputArgument::OPTIONAL, 'steps')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chatId = $input->getArgument('chatId');
        $steps = $input->getArgument('steps');
        $chat = $this->em->getRepository(Chat::class)->findOneBy(['id'=> $chatId]);
        $page = $input->getArgument('page');
        $messages = $this->em->getRepository(ChatMessage::class)->findBy(['chat'=>$chat],["id"=>'desc'],$steps,$page);

        $jsonContent = $this->serializer->serialize($messages, 'json', ['groups' => [$this->getName()]]);
        $output->write($jsonContent);
        return Command::SUCCESS;
    }
}
