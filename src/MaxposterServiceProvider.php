<?php

namespace Pathfinder\LaravelMaxposter;

use Illuminate\Support\ServiceProvider;

class MaxposterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Pathfinder\LaravelMaxposter\MaxposterService');
    }
}
