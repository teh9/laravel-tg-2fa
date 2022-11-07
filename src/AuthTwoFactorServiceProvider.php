<?php

namespace Teh9\Laravel2fa;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AuthTwoFactorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel2fa')
            ->hasConfigFile()
            ->hasMigration('add_chat_id_column_to_model_table')
            ->hasMigration('add_secret_column_to_model_table');
    }
}
