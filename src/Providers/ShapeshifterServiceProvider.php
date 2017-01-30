<?php

namespace Just\Shapeshifter\Providers;

use Illuminate\Support\ServiceProvider;

class ShapeshifterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        die();
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/skeleton.php' => config_path('shapeshifter.php'),
            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'skeleton');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/skeleton'),
            ], 'views');
            */
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'shapeshifter');
    }
}
