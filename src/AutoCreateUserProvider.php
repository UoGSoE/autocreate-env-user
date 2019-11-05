<?php

namespace UoGSoE\UserFromEnv;

class AutoCreateUserProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutoCreateUserCommand::class,
            ]);
        }
    }
}