<?php

namespace App\Api\RestfulJsonApi\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Mapping\ClassMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Doctrine\Common\Annotations\AnnotationReader as DocReader;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use App\Entity\User;
use App\Api\RestfulJsonApi\RestfulCommand as Base;
#[AsCommand(
    name: 'rest:head',
    description: 'Add a short description for your command',
)]
class HeadCommand extends Base
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $class = $this->entities->getEntity($input->getArgument('entity'));
    
        
        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();
        $propertyInfo = new PropertyInfoExtractor([$reflectionExtractor,], [$phpDocExtractor,]);
        $reflector = new \ReflectionClass($class);
        $docReader = new DocReader();
        $docReader->getClassAnnotations($reflector);
        $data = $propertyInfo->getProperties($class);
      // $classMetadata = new ClassMetadata($entity);
        $jsonContent = $this->serializer->serialize($data, 'json');
        $output->write($jsonContent);
        return Command::SUCCESS;
    }
}
