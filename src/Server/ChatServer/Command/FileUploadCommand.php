<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\ChatServer\Command;

use App\Entity\File;
use App\Server\ChatServer\ChatCommand as AbstractCommand;
use App\Server\JsonWebsocketServer\CommandException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * FileUploadCommand
 */
#[AsCommand(
    name: 'file:upload',
    description: 'Uploads a file',
)]
class FileUploadCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('filename', InputArgument::REQUIRED, 'Argument description')
            ->addArgument('content', InputArgument::REQUIRED, 'Argument description');
    }

    /**
     * @param InputInterface $input
     * @return string
     * @throws CommandException
     */
    public function handle(InputInterface $input): string
    {
        $user = $this->authorize($input->getArgument('token'));

        $file = new File();

        $file->setUser($user);
        $file->setFilename($input->getArgument('filename'));
        $file->setContent($input->getArgument('content'));

        $this->em->persist($file);
        $this->em->flush();
        return (string)$file->getId();
    }
}
