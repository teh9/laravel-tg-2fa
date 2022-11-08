<?php

namespace Teh9\Laravel2fa;

use Illuminate\Support\ServiceProvider;

class TelegramTwoFactorServiceProvider extends ServiceProvider
{
    public function boot ()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel2fa.php' => config_path('laravel2fa.php'),
        ], 'config');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang/', 'laravel2fa');

        $this->publishMigrations();
    }

    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }

    private function getMigrationsPath()
    {
        return __DIR__ . '/../database/migrations/';
    }
}
