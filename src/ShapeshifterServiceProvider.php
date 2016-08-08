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
use Krucas\Notification\Facades\Notification;
use Krucas\Notification\Middleware\NotificationMiddleware;
use Krucas\Notification\NotificationServiceProvider;

class ShapeshifterServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    private $resources_path = __DIR__.'/../resources/';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @param Kernel $kernel
     */
    public function boot(Kernel $kernel)
    {
        $this->loadViewsFrom($this->resources_path.'views', self::PACKAGE_NAMESPACE);
        $this->loadTranslationsFrom($this->resources_path.'lang', self::PACKAGE_NAMESPACE);
        $this->mergeConfigFrom($this->resources_path.'config/shapeshifter.php', self::PACKAGE_NAMESPACE);

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

        $kernel->pushMiddleware(NotificationMiddleware::class);
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

    /**
     * Register the Aliases.
     */
    private function registerAliasses()
    {
        AliasLoader::getInstance()->alias('Form', FormFacade::class);
        AliasLoader::getInstance()->alias('Html', HtmlFacade::class);
        AliasLoader::getInstance()->alias('Sentinel', Sentinel::class);
        AliasLoader::getInstance()->alias('Notification', Notification::class);
    }

    /**
     * Registering the dependend ServiceProvider(s).
     */
    private function registerServiceProviders()
    {
        $this->app->register(HtmlServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(ElfinderServiceProvider::class);
    }
}
