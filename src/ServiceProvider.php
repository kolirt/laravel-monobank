<?php

namespace Kolirt\Monobank;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected $commands = [
        Commands\InstallCommand::class
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/monobank.php', 'monobank');

        $this->publishes([
            __DIR__ . '/../config/monobank.php' => config_path('monobank.php')
        ]);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->commands($this->commands);

        app()->bind('monobank', function () {
            return new Monobank();
        });
    }
}