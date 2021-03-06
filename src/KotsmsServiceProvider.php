<?php

namespace Hosomikai\Kotsms;

use Illuminate\Support\ServiceProvider;

class KotsmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/kotsms.php' => config_path('kotsms.php'),
            ], 'kotsms-config');
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/kotsms.php', 'kotsms');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('kotsms', function ($app) {
            return new Kotsms();
        });
    }
}
