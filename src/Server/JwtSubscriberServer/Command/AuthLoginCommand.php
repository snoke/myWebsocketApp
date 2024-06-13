<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Server\JwtSubscriberServer\Command;

use App\Server\JsonWebsocketServer\CommandException;
use App\Server\JwtSubscriberServer\SubscriberBroadcastCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * AuthLoginCommand
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
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws CommandException
     * @throws ClientExceptionInterface
     */
    public function handle(InputInterface $input): string
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
            throw new CommandException('login failed', $statusCode);
        }
        $content = $response->toArray();

        return $content['token'];
    }
}