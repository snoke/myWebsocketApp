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

use App\Command\AbstractCommand;

use App\Entity\ChatMessage;

#[AsCommand(
    name: 'chat:message:status',
    description: "Sets a Message Status",
)]
class ChatMessageStatusCommand extends AbstractCommand
{
    
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        parent::__construct($em,$serializer);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('messageId', InputArgument::OPTIONAL, "the ChatMessage ID")
            ->addArgument('status', InputArgument::OPTIONAL, "possible values are: 'sent','delivered','seen'")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $status = $input->getArgument('status');
        $id = $input->getArgument('messageId');

        $message= $this->em->getRepository(ChatMessage::class)->findOneBy(['id'=>$id]);
        $message->setStatus($status);
        $message->__set($status,new \DateTime());
        
        $this->em->persist($message);
        $this->em->flush();

        $jsonContent = $this->serializer->serialize($message, 'json', ['groups' => ['chat_message_status']]);
        $output->write($jsonContent);
        return Command::SUCCESS;

    }
}
