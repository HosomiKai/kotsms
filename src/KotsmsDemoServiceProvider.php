<?php

namespace Hosomikai\Kotsms;

use Illuminate\Support\ServiceProvider;

class KotsmsDemoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../routes/demo.php';

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Kotsms');
    }
}
