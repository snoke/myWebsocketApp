<?php
/*
 * Author: Stefan Sander <mail@stefan-sander.online>
 * 
 * this command is being called when npm run dev or npm run build is called
 * it will create a static html for capacitor to build a native android app
 */

namespace App\Tools\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * AppClearApk
 */
#[AsCommand(
    name: 'app:clear:apk',
    description: 'Moves recently built apk into downloads, clears all android build data',
)]
class AppClearApk extends Command
{
    /**
     * @param $dir
     * @return void
     */
    private function rrmdir($dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . '/' . $object))
                        $this->rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                    else
                        unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
            rmdir($dir);
        }
    }

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dir = __DIR__ . '/../..';
        if(file_exists($dir . '/public/index.html')) {
            unlink($dir . '/public/index.html');
        }
        if(file_exists($dir . '/public/downloads/android-client-latest.apk')) {
            unlink($dir . '/public/downloads/android-client-latest.apk');
        }
        if(file_exists($dir . '/android/app/build/outputs/apk/debug/app-debug.apk')) {
            rename($dir . '/android/app/build/outputs/apk/debug/app-debug.apk', $dir . '/public/downloads/android-client-latest.apk');
        }
        if(file_exists($dir . '/capacitor.config.ts')) {
            unlink($dir . '/capacitor.config.ts');
        }
        if (file_exists($dir . '/android') && is_dir($dir . '/android')) {
            $this->rrmdir($dir . '/android');
        }
        return Command::SUCCESS;
    }
}
