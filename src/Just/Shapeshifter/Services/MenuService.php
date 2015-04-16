<?php namespace Just\Shapeshifter\Services;

use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

use Sentry;

class MenuService
{
	/**
	 * @var Application
	 */
	public $app;

	/**
	 * @var Collection
	 */
	public $collection;

	public function __construct (Application $app, Collection $collection)
	{
		$this->app        = $app;
		$this->collection = $collection;
	}

	public function generateMenu ()
	{
		$user = Sentry::getUser();

		$config = $this->collection->make($this->app['config']->get('shapeshifter::config.menu'));

		$config = $config->reject(function ($item) use ($user) {
			if (count($item['children'])) {
				foreach ($item['children'] as $key => $child) {
					if ($user->hasAccess('admin.' . $child['url'] . '.index')) {
						return false;
					}
				}
				return true;

			} else {
				return ! $user->hasAccess('admin.' . $item['url'] . '.index');
			}
		});

		$reference = $this;
		$config->transform(function ($config) use ($reference, $user)
		{
			$config['active'] = $reference->app['request']->segment(2) === $config['url'];
			if (isset($config['children']) && count($config['children']) > 0)
			{
				$config['children'] = $reference->collection->make($config['children']);
				$config['children'] = $config['children']->reject(function ($child) use ($user) {
					return ! $user->hasAccess('admin.' . $child['url'] . '.index');
				});

				$config['children']->transform(function ($child) use (&$config, $reference)
				{

					$child['active'] = ($reference->app['request']->segment(2) === $child['url']);
					if ($child['active'])
					{
						$config['active'] = true;
					} else
					{
						$child['active'] = \Session::get('category') == last(explode('=', last(explode('?', $child['url']))));
						if ($child['active'])
						{
							$config['active'] = true;
						}
					}

					return $child;
				});
			}

			return $config;
		});

		return $config;
	}
}
