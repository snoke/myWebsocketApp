<?php

namespace App\Api\RestfulJsonApi\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Api\RestfulJsonApi\RestfulCommand as Base;
#[AsCommand(
    name: 'rest:post',
    description: 'Add a short description for your command',
)]
class PostCommand extends Base
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('data', InputArgument::REQUIRED, 'data')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $input->getArgument('data');

        $entity = $this->entities->getEntity($input->getArgument('entity'));
        $id = $input->getArgument('id');
        $object = new $entity();
        $object = $this->em->getRepository($entity)->findOneBy(['id'=> $id]);
        foreach($data as $k => $v) {
            $object->__set($k,$v);
        }
        $this->em->persist($object);
        $this->em->flush();
        $output->write($object->getId());
        return Command::SUCCESS;
    }
}
