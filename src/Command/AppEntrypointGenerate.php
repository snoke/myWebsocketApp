<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 * 
 * this command is being called when npm run dev or npm run build is called
 * it will create a static html for capacitor to build a native android app
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *
 */
#[AsCommand(
    name: 'app:entrypoint:generate',
    description: 'Create a static index.html entrypoint for capacitor',
)]
class AppEntrypointGenerate extends Command
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('GET', $_ENV['SERVER_URL'] . '/client/app');
        $content = $response->getContent();
        file_put_contents(__DIR__ . '/../../public/index.html', str_replace('/build', 'build', $content));
        return Command::SUCCESS;
    }
}
