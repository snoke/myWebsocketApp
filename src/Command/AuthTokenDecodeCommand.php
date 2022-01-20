<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
#[AsCommand(
    name: 'auth:token:decode',
    description: 'Extracts Claim from provided Token',
)]
class AuthTokenDecodeCommand extends Command
{
    public function __construct(JWTEncoderInterface $encoder) {
        parent::__construct();
        $this->encoder = $encoder;
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
