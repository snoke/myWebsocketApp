<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;


#[AsCommand(
    name: 'auth:login',
    description: 'Authenticate with provided credentials and retrieve JWT',
)]
class AuthLoginCommand extends Command
{    private $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('loginName', InputArgument::REQUIRED, 'login username')
            ->addArgument('password', InputArgument::REQUIRED, 'login password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $loginName = $input->getArgument('loginName');
        $password = $input->getArgument('password');
        
        
        $response = $this->client->request(
            'POST',
            $_ENV['SERVER_URL'] . '/api/login_check',
            [    'headers' => [
                'Accept' => 'application/json',
            ],
                'json' => [
                    'username' => $loginName,
                    'password' => $password,
                ],
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        $output->write($content["token"]);
        return Command::SUCCESS;
    }
}
