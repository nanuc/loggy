<?php

namespace Nanuc\Loggy\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loggy:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Loggy test message';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        loggy("Hi! This is a test message from " . $this->laravel['config']['app.name']);
        loggy([
            'message' => 'You can also use JSON',
            'great-frameworks' => ['laravel']
        ]);
    }
}
