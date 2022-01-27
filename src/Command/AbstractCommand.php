<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Attribute\AsCommand;
#[AsCommand(
    name: 'abstract',
)]
class AbstractCommand extends Command
{

    protected $em;
    protected $serializer;
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer) {
        parent::__construct();

        $this->em = $em;
        $this->serializer = $serializer;
    }
}
