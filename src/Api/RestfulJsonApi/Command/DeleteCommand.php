<?php

namespace App\Api\RestfulJsonApi\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Api\RestfulJsonApi\RestfulCommand as Base;
#[AsCommand(
    name: 'rest:delete',
    description: 'Add a short description for your command',
)]
class DeleteCommand extends Base
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'id')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entity = $this->entities->getEntity($input->getArgument('entity'));
        $id = $input->getArgument('id');
        $object = $this->em->getRepository($entity)->findOneBy(['id'=> $id]);
        $this->em->remove($object);
        $this->em->flush();
        return Command::SUCCESS;
    }
}
