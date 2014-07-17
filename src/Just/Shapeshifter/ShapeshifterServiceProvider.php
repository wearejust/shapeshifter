<?php namespace Just\Shapeshifter;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ShapeshifterServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->package('just/shapeshifter');
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

        $this->overridePackageDefaults();
        $this->requireBootstrapFiles();
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('shapeshifter');
	}

    /**
     *  Override the default values of Sentry
     *
     *  @return void
     */
    protected function overridePackageDefaults()
    {
        $this->app['config']->set('cartalyst/sentry::throttling.enabled', ($this->app->environment() !== 'local'));
        $this->app['config']->set('cartalyst/sentry::users.model', 'Just\Shapeshifter\Core\Models\User');
        $this->app['config']->set('cartalyst/sentry::groups.model', 'Just\Shapeshifter\Core\Models\Group');
        $this->app['config']->set('cartalyst/sentry::throttling.model', 'Just\Shapeshifter\Core\Models\Throttle');
        $this->app['config']->set('cartalyst/sentry::user_groups_pivot_table', 'cms_users_groups');
    }

    /**
     *  Require some files
     *
     * @return void
     */
    protected function requireBootstrapFiles()
    {
        require_once __DIR__ . '/../../filters.php';
        require_once __DIR__ . '/../../routes.php';
    }

    private function registerAliasses()
    {
        AliasLoader::getInstance()->alias('Sentry', 'Cartalyst\Sentry\Facades\Laravel\Sentry');
        AliasLoader::getInstance()->alias('Notification', 'Krucas\Notification\Facades\Notification');
    }

    private function registerServiceProviders()
    {
        $this->app->register('Cartalyst\Sentry\SentryServiceProvider');
        $this->app->register('Krucas\Notification\NotificationServiceProvider');
        $this->app->register('Barryvdh\Elfinder\ElfinderServiceProvider');
    }
}


