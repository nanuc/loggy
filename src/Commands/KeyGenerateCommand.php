<?php

namespace Nanuc\Loggy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class KeyGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loggy:key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Loggy key';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = Str::random(config('loggy.default-key-length'));

        // Next, we will replace the loggy key in the environment file so it is
        // automatically setup for this developer.
        if (! $this->setKeyInEnvironmentFile($key)) {
            return;
        }

        $this->laravel['config']['loggy.key'] = $key;

        $this->info('Loggy key set successfully. Happy logging at:' . PHP_EOL . $this->laravel['config']['loggy.url'] . '/' . $key);
    }

    /**
     * Set the application key in the environment file.
     *
     * @param  string  $key
     * @return bool
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $this->writeNewEnvironmentFileWith($key);

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param  string  $key
     * @return void
     */
    protected function writeNewEnvironmentFileWith($key)
    {
        $envContents = file_get_contents($this->laravel->environmentFilePath());
        if(preg_match($this->keyReplacementPattern(), $envContents) == 1) {
            file_put_contents($this->laravel->environmentFilePath(), preg_replace(
                $this->keyReplacementPattern(),
                'LOGGY_KEY='.$key,
                $envContents
            ));
        }
        else {
            file_put_contents($this->laravel->environmentFilePath(), PHP_EOL.'LOGGY_KEY='.$key, FILE_APPEND);
        }


    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @return string
     */
    protected function keyReplacementPattern()
    {
        $escaped = preg_quote('='.$this->laravel['config']['loggy.key'], '/');

        return "/^LOGGY_KEY{$escaped}/m";
    }
}
