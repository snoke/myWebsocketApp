<?php

namespace App\Api\RestfulJsonApi;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Api\RestfulJsonApi\EntityCollection;
abstract class RestfulCommand extends Command
{
    protected $entities;
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer,EntityCollection $entities) {
        
        parent::__construct();
        $this->entities = $entities;
        $this->em = $em;
        $this->serializer = $serializer;
    }
    protected function configure(): void
    {
        $this
            ->addArgument('entity', InputArgument::REQUIRED, 'entity')
        ;
    }

}
