<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Tools\Command;

use App\Tools\Environment;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * AppInstall
 */
#[AsCommand(
    name: 'app:install',
    description: 'install dependencies, set up database, jwt keypairs and assets',
)]
class AppInstall extends Command
{
    private ?string $message = null;
    private ValidatorInterface $validator;
    private Environment $environment;
    private SymfonyStyle $io;
    private OutputInterface $output;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct();
        $this->validator = $validator;
    }

    /**
     * @param $name
     * @return array|bool|float|int|mixed|string|null
     */
    private function askFor($name): mixed
    {
        $value = $this->io->ask($name, $this->environment->__get($name));
        $oldValue = $this->environment->__get($name);
        $this->environment->__set($name, $value);
        $errors = $this->validator->validate($this->environment);
        if (count($errors) > 0) {
            $this->showHeader($this->output);
            $this->io->error('`' . $value . '` is not valid for ´' . $name . '´');
            $this->environment->__set($name, $oldValue);
            return $this->askFor($name);
        }
        return $value;
    }

    /**
     * @param $output
     * @return void
     */
    private function showHeader($output): void
    {
        $this->output->write(sprintf("\033\143"));
        $this->io->title('Set up');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->io= new SymfonyStyle($input, $output);
        $this->showHeader($output);
        if ($this->message !== null) {
            $this->io->error($this->message);
            $this->message = null;
        }
        $this->environment = new Environment(
            'http://localhost',
            'ws://localhost:8080',
            'localhost:3306',
            'myWebsocketApp',
            'root',
            '',
            'mysql://root@localhost:3306/myWebsocketApp',
        );
        $host = $this->askFor('databaseHost');
        $this->showHeader($output);
        $database = $this->askFor('database');
        $this->showHeader($output);
        $user = $this->askFor('databaseUser');
        $this->showHeader($output);
        $password = $this->askFor('databasePassword');
        $this->showHeader($output);
        mysqli_report(MYSQLI_REPORT_OFF);
        $connection = @\mysqli_connect($host, $user, $password);
        if (!$connection) {
            $this->message = 'could not connect to Database';
            return $this->execute($input, $output);
        }
        $dbinfo = explode('-', mysqli_get_server_info($connection));

        if (strlen($password) > 0) {
            $password = ':' . $password;
        }
        if (isset($dbinfo[2])) {
            $version = strtolower($dbinfo[2]) . '-' . $dbinfo[1];
        } else {
            $version = $dbinfo[1];
        }
        $this->environment->setDatabaseUrl('mysql://' . $user . $password . '@' . $host . '/' . $database . '?serverVersion=' . strtolower($version));

        $output->write("\033\143");
        $this->io->title('Set up');
        $str = 'SERVER_URL=\'' . $this->askFor('ServerUrl') . "'\n";

        $this->showHeader($output);
        $str .= 'WEBSOCKET_URL=\'' . $this->askFor('WebsocketUrl') . "'\n";

        $this->showHeader($output);
        $str .= 'DATABASE_URL=\'' . $this->environment->getDatabaseUrl() . "'\n";

        if (file_exists(__DIR__ . '/../../.env.local')) {
            unlink(__DIR__ . '/../../.env.local');
        }

        if (file_put_contents(__DIR__ . '/../../.env.local', $str)) {

            $this->io->block('config set up success');
            return Command::SUCCESS;
        } else {

            $this->io->error('could not write config file');
            return Command::FAILURE;
        }

    }

}