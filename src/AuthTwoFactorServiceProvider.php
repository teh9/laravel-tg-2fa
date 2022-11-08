<?php

namespace Teh9\Laravel2fa;

use Illuminate\Support\ServiceProvider;

class AuthTwoFactorServiceProvider extends ServiceProvider
{
    public function boot ()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel2fa.php' => config_path('laravel2fa.php'),
        ], 'config');
    }
}
