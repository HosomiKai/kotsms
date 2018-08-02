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
        include(__DIR__ . '/routes.php');

        $this->loadViewsFrom(__DIR__ . '/Views', 'Kotsms');

        $this->publishes([
            __DIR__ . '/Config/kotsms.php' => config_path('kotsms.php'),
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Facade => Custom Class
        $this->app->singleton('kotsms', function ($app) {
            return new Kotsms();
        });
    }
}
