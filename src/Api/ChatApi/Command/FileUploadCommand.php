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
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\File;
use App\Entity\User;

use App\Api\ChatApi\ChatCommand as AbstractCommand;
#[AsCommand(
    name: 'file:upload',
    description: 'Uploads a file',
)]
class FileUploadCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('content', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $input->getArgument('token');
        $user = $this->getUserByToken($token);
        if (!$user) { return 401; }
        $io = new SymfonyStyle($input, $output);
        
        $file = new File();

        $file->setUser($user);
        $file->setFilename($input->getArgument('filename'));
        $file->setContent($input->getArgument('content'));

        $this->em->persist($file);
        $this->em->flush();
        $output->write($file->getId());
        return Command::SUCCESS;
    }
}
