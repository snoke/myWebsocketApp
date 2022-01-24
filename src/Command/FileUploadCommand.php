<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\File;
use App\Entity\User;

#[AsCommand(
    name: 'app:file:upload',
    description: 'Uploads a file',
)]
class FileUploadCommand extends Command
{
    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->em = $em;

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
