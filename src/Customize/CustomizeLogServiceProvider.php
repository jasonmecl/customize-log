<?php

namespace Customize;

use Illuminate\Support\ServiceProvider;

class CustomizeLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $configPath = __DIR__ . '/Config/logging.php';
        $publishPath = config_path('logging.php');

        $this->publishes([$configPath => $publishPath], 'config');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('customizeLog', function () {
            return new CustomizeLog($this->app);
        });
    }
}
