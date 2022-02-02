<?php
namespace App\Websocket\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\User;


use App\Websocket\WebsocketCommand as AbstractCommand;
#[AsCommand(
    name: 'user:contacts',
    description: 'gets user contacts',
)]
class UserContactsCommand extends AbstractCommand
{  

    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        
        parent::__construct();
        
        $this->em = $em;
        $this->serializer = $serializer;
    }

protected function configure(): void
{
    $this
        ->addArgument('userId', InputArgument::REQUIRED, 'userid')
    ;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
    $this->em->clear();
    $users = $this->em->getRepository(User::class);
    $userId = $input->getArgument('userId');
    $user = $users->findOneBy(['id'=> $userId]);
    $contacts = $user->getContacts();
    $jsonContent = $this->serializer->serialize($contacts, 'json', ['groups' => ['app_user_contacts']]);

    $output->write($jsonContent);
    
    return Command::SUCCESS;
}
}
