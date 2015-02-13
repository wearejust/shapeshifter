<?php namespace Just\Shapeshifter\Providers;

use Illuminate\Support\ServiceProvider;
use Just\Shapeshifter\Core\Models\Language;
use Illuminate\Foundation\Application;
use Just\Shapeshifter\Exceptions\NoActiveLanguagesException;
use Just\Shapeshifter\Exceptions\NoDefaultLanguageException;
use Session;

class LanguageServiceProvider extends ServiceProvider
{


	/**
	 * @var Language
	 */
	private $lang;
	/**
	 * @var Application
	 */
	protected $app;

	function __construct (Application $app)
	{
		$this->lang = new Language;
		$this->app  = $app;
	}

	public function register () { }

	public function  boot ()
	{
		if(\Config::get('shapeshifter::config.translation') && \Schema::hasTable('languages'))
		{
			$this->validateDependencies();
			if(!Session::has('active_lang')) {
				$default_lang = $this->lang->where('short_code', '=', $this->app['config']->get('app.locale'))->first();
				Session::put('active_lang', $default_lang);
			}
		}
	}

	private function validateDependencies ()
	{

		/**
		 * Check if there is an language available in the database.
		 */
		$allActiveLanguages = $this->lang->where('active', '=', '1');
		if ($allActiveLanguages->count() == 0)
		{
			throw new NoActiveLanguagesException("There is no active language in the database. Active an language in the database.");
		}

		/**
		 * Check if the locale set in config/app.php matches a row in the database.
		 */
		$default_locale = $this->app['config']->get('app.locale');
		$defaultLocaleCount = $this->lang->where('short_code', '=', $default_locale);
		if ($defaultLocaleCount->count() == 0)
		{
			throw new NoDefaultLanguageException("The locale {$default_locale} does not exists in the database.");
		}
	}

}