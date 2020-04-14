<?php

namespace Nanuc\Loggy;

use Illuminate\Support\ServiceProvider;
use Nanuc\Loggy\Commands\KeyGenerateCommand;
use Nanuc\Loggy\Commands\TestCommand;

class LoggyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/loggy.php' => base_path('config/loggy.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                KeyGenerateCommand::class,
                TestCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/loggy.php', 'loggy');

        $this->app->singleton('loggy', function(){
            return new Loggy(config('loggy.url'), config('loggy.key'));
        });
    }
}