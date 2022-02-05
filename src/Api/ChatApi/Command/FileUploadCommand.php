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

use App\Entity\File;
use App\Entity\User;

use App\Api\JwtSubscriberApi\SubscriberBroadcastCommand as AbstractCommand;
#[AsCommand(
    name: 'file:upload',
    description: 'Uploads a file',
)]
class FileUploadCommand extends AbstractCommand
{
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        parent::__construct();
        
        $this->em = $em;
        $this->serializer = $serializer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('filename', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('content', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $file = new File();

        $file->setUser($this->em->getRepository(User::class)->findOneBy(['id'=> $input->getArgument('userId')]));
        $file->setFilename($input->getArgument('filename'));
        $file->setContent($input->getArgument('content'));

        $this->em->persist($file);
        $this->em->flush();
        $output->write($file->getId());
        return Command::SUCCESS;
    }
}
