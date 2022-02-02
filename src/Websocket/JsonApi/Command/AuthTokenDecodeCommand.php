<?php

namespace App\Websocket\JsonApi\Command;

use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use App\Websocket\WebsocketCommand as AbstractCommand;
#[AsCommand(
    name: 'auth:token:decode',
    description: 'Extracts Claim from provided Token',
)]
class AuthTokenDecodeCommand extends AbstractCommand
{
    public function __construct(EntityManagerInterface $em,SerializerInterface $serializer,JWTEncoderInterface $encoder) {
        
        parent::__construct();
        
        $this->encoder = $encoder;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('token', InputArgument::REQUIRED, 'the token to extract the claim')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $token = $input->getArgument('token');
        $payload = $this->encoder->decode($token);

        $output->write(json_encode($payload));
        return Command::SUCCESS;
    }
}
