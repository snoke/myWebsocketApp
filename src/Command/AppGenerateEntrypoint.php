<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Contracts\HttpClient\HttpClientInterface;
#[AsCommand(
    name: 'app:generate:entrypoint',
    description: 'Create a static index.html entrypoint for capacitor',
)]
class AppGenerateIndex extends Command
{    private $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->client->request('POST', $_ENV['SERVER_URL'], ['headers' => ['Accept' => 'application/json',]]);
        
        $content = $response->getContent();
        file_put_contents(__DIR__.'/../../public/index.html', str_replace('/build','build',$content));
        return Command::SUCCESS;
    }
}
