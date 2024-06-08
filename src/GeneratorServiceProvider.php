<?php

namespace Usermp\LaravelGenerator;

use Illuminate\Support\ServiceProvider;

class LaravelGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Usermp\LaravelGenerator\Commands\GenerateComponents::class,
            ]);
        }
    }

    public function register()
    {

    }
}