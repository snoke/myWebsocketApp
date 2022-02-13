<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Console\Question\Question;
use App\Entity\Installer;
#[AsCommand(
    name: 'app:install',
    description: 'install dependencies, set up database, jwt keypairs and assets',
)]
class AppInstall extends Command
{    
  public function __construct(ValidatorInterface $validator) {
    parent::__construct();
    $this->validator = $validator;
  }
  private function askFor($name) {
    $value = $this->io->ask($name,$this->installer->__get($name));
    $oldValue = $this->installer->__get($name);
    $this->installer->__set($name,$value);
    $errors = $this->validator->validate($this->installer);
    if (count($errors) > 0) {

        $this->io->write((string) $errors);
        $this->installer->__set($name,$oldValue);
        return $this->askFor($name);
    }
    return $value;
  }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {       
      $this->io= new SymfonyStyle($input, $output);  
      $this->installer = new Installer(
        'http://localhost',
        'ws://localhost:8080',
        'localhost:3306',
        'root',
        '',
        'mysql://root@localhost:3306/myWebsocketApp',
      ); 
      $host = $this->askFor('databaseHost');
      $user = $this->askFor('databaseUser');
      $password = $this->askFor('databasePassword');
      mysqli_report(MYSQLI_REPORT_OFF);
      $db = @\mysqli_connect($host, $user, $password);
      if (!$db) {
          $this->io->error("could not connect to Database");
          $this->execute($input,$output);
        }
      // Output the MySQL version
      $dbinfo = explode( '-', mysqli_get_server_info($db)) ;
      // Close connection
      if (strlen($password)>0) {
        $password = ':' . $password;
      }
      var_dump(mysqli_get_server_info($db));
      var_dump($dbinfo);
      $this->installer->setDatabaseUrl("mysql://".$user .$password."@".$host."/myWebsocketApp?serverVersion=".strtolower($dbinfo[2])."-".$dbinfo[1]);

      mysqli_close($db);
       $errors = $this->validator->validate($this->installer);
      
      if (count($errors) > 0) {
        return $this->execute($input,$output);
    }
      $str = 'SERVER_URL=\''.$this->askFor('ServerUrl') . "'\n";
      $str .= 'WEBSOCKET_URL=\''.$this->askFor('WebsocketUrl') . "'\n";
      $str .= 'DATABASE_URL=\''.$this->installer->getDatabaseUrl() . "'\n";
      file_exists(__DIR__.'/../../.env.local')?unlink(__DIR__.'/../../.env.local'):null;
      file_put_contents(__DIR__.'/../../.env.local', $str);

        return Command::SUCCESS;
    }
}
