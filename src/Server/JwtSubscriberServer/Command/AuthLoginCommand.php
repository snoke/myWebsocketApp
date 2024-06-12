<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer\Command;

use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
#[AsCommand(
    name: 'auth:login',
    description: 'Authenticate with provided credentials and retrieve JWT',
)]
class AuthLoginCommand extends SubscriberBroadcastCommand
{
    private HttpClientInterface $client;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('loginName', InputArgument::REQUIRED, 'login username')
            ->addArgument('password', InputArgument::REQUIRED, 'login password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loginName = $input->getArgument('loginName');
        $password = $input->getArgument('password');

        $response = $this->client->request(
            'POST',
            $_ENV['SERVER_URL'] . '/api/login_check',
            ['headers' => [
                'Accept' => 'application/json',
            ],
                'json' => [
                    'username' => $loginName,
                    'password' => $password,
                ],
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 401) {
            $output->write('login failed');
            return Command::FAILURE;
        }
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();

        $output->write($content['token']);
        return Command::SUCCESS;
    }
}