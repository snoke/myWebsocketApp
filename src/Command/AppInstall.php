<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Installer;

/**
 *
 */
#[AsCommand(
    name: 'app:install',
    description: 'install dependencies, set up database, jwt keypairs and assets',
)]
class AppInstall extends Command
{
    private ?string $message;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct();
        $this->validator = $validator;
    }

    /**
     * @param $name
     * @return array|bool|float|int|mixed|string|null
     */
    private function askFor($name)
    {
        $value = $this->io->ask($name, $this->installer->__get($name));
        $oldValue = $this->installer->__get($name);
        $this->installer->__set($name, $value);
        $errors = $this->validator->validate($this->installer);
        if (count($errors) > 0) {
            $this->showHeader($this->output);
            $this->io->error('`' . $value . '` is not valid for ´' . $name . '´');
            $this->installer->__set($name, $oldValue);
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
        $this->showHeader($output);
        if ($this->message) {
            $this->io->error($this->message);
            $this->message = null;
        }
        $this->installer = new Installer(
            'http://localhost',
            'ws://localhost:8080',
            'localhost:3306',
            'root',
            '',
            'mysql://root@localhost:3306/myWebsocketApp',
        );
        $host = $this->askFor('databaseHost');
        $this->showHeader($output);
        $user = $this->askFor('databaseUser');
        $this->showHeader($output);
        $password = $this->askFor('databasePassword');
        $this->showHeader($output);
        mysqli_report(MYSQLI_REPORT_OFF);
        $db = @\mysqli_connect($host, $user, $password);
        if (!$db) {
            $this->message = 'could not connect to Database';
            return $this->execute($input, $output);
        }
        $dbinfo = explode('-', mysqli_get_server_info($db));

        if (strlen($password) > 0) {
            $password = ':' . $password;
        }
        if (isset($dbinfo[2])) {
            $version = strtolower($dbinfo[2]) . '-' . $dbinfo[1];
        } else {
            $version = $dbinfo[1];
        }
        $this->installer->setDatabaseUrl('mysql://' . $user . $password . '@' . $host . '/myWebsocketApp?serverVersion=' . strtolower($version));

        $output->write(sprintf("\033\143"));
        $this->io->title('Set up');
        $str = 'SERVER_URL=\'' . $this->askFor('ServerUrl') . "'\n";

        $this->showHeader($output);
        $str .= 'WEBSOCKET_URL=\'' . $this->askFor('WebsocketUrl') . "'\n";

        $this->showHeader($output);
        $str .= 'DATABASE_URL=\'' . $this->installer->getDatabaseUrl() . "'\n";

        file_exists(__DIR__ . '/../../.env.local') ? unlink(__DIR__ . '/../../.env.local') : null;
        if (file_put_contents(__DIR__ . '/../../.env.local', $str)) {

            $this->io->block('config set up success');
            return Command::SUCCESS;
        } else {

            $this->io->error('could not write config file');
            return Command::FAILURE;
        }

    }
}