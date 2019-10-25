<?php

namespace Customize;

use Illuminate\Support\ServiceProvider;

class CustomizeLogServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('customizeLog', function () {
            return new CustomizeLog();
        });
    }
}
