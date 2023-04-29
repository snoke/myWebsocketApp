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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Contracts\HttpClient\HttpClientInterface;
#[AsCommand(
    name: 'app:entrypoint:clear',
    description: 'Moves recently built apk into downloads, clears all android build data',
)]
class AppEntrypointClear extends Command
{    
   private function rrmdir($dir) { 
    if (is_dir($dir)) { 
      $objects = scandir($dir);
      foreach ($objects as $object) { 
        if ($object != "." && $object != "..") { 
          if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
            $this->rrmdir($dir. DIRECTORY_SEPARATOR .$object);
          else
            unlink($dir. DIRECTORY_SEPARATOR .$object); 
        } 
      }
      rmdir($dir); 
    } 
  }
   private $client;

    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    { 
        file_exists(__DIR__.'/../../public/index.html')?unlink(__DIR__.'/../../public/index.html'):null;
        file_exists(__DIR__.'/../../public/downloads/android-client-latest.apk')?unlink(__DIR__.'/../../public/downloads/android-client-latest.apk'):null;
        file_exists(__DIR__.'/../../android/app/build/outputs/apk/debug/app-debug.apk')?rename(__DIR__.'/../../android/app/build/outputs/apk/debug/app-debug.apk', __DIR__.'/../../public/downloads/android-client-latest.apk'):null;
        file_exists(__DIR__.'/../../capacitor.config.ts')?unlink(__DIR__.'/../../capacitor.config.ts'):null;
        if (file_exists(__DIR__.'/../../android') && is_dir(__DIR__.'/../../android')) {
            $this->rrmdir(__DIR__.'/../../android');
        }
        return Command::SUCCESS;
    }
}
