<?php

namespace Just\Shapeshifter;

use Barryvdh\Elfinder\ElfinderServiceProvider;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Collective\Html\FormFacade;
use Collective\Html\HtmlFacade;
use Collective\Html\HtmlServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laracasts\Flash\FlashServiceProvider;

class ShapeshifterServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Location of the Providers folder from the current __DIR__
     */
    protected $providersPath = '/Providers';

    /**
     * Bootstrap the application events.
     *
     * @param Kernel $kernel
     */
    public function boot(Kernel $kernel)
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'shapeshifter');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'shapeshifter');
        $this->mergeConfigFrom(__DIR__ . '/../resources/config/shapeshifter.php', 'shapeshifter');

        $this->publishes(
            [
                __DIR__ . '/../resources/config/shapeshifter.php' => config_path('shapeshifter.php'),
                __DIR__ . '/../resources/lang'                    => base_path('resources/lang/vendor/shapeshifter'),
            ],
            'core'
        );

        $this->publishes(
            [
                __DIR__ . '/../public' => public_path('packages/just/shapeshifter'),
            ],
            'public'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServiceProviders();
        $this->registerAliasses();
        $this->requireBootstrapFiles();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['shapeshifter'];
    }

    /**
     *  Require some files
     *
     * @return void
     */
    protected function requireBootstrapFiles()
    {
        require_once __DIR__ . '/routes.php';
    }

    private function registerAliasses()
    {
        AliasLoader::getInstance()->alias('Form', FormFacade::class);
        AliasLoader::getInstance()->alias('Html', HtmlFacade::class);
        AliasLoader::getInstance()->alias('Sentinel', Sentinel::class);
    }

    private function registerServiceProviders()
    {
        $this->app->register(HtmlServiceProvider::class);
        $this->app->register(FlashServiceProvider::class);
        $this->app->register(ElfinderServiceProvider::class);
    }
}
