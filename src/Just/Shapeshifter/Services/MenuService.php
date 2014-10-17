<?php namespace Just\Shapeshifter\Services;

use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

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

	public function __construct(Application $app, Collection $collection)
	{
		$this->app = $app;
		$this->collection = $collection;
	}

	public function generateMenu()
	{
		$config = $this->collection->make($this->app['config']->get('shapeshifter::config.menu'));

		$reference = $this;
		$config->transform(function($config) use ($reference)
		{
			$config['active'] = $reference->app['request']->segment(2) === $config['url'];

			$config['children'] = $reference->collection->make($config['children']);
			$config['children']->transform(function($child) use (&$config, $reference)
			{

				$child['active'] = ($reference->app['request']->segment(2) === $child['url']);
				if ($child['active']) {
					$config['active'] = true;
				}else {
					$child['active'] = \Session::get('category') == last(explode('=', last(explode('?', $child['url']))));
					if ($child['active']){
						$config['active'] = true;
					}
				}

				return $child;
			});

			return $config;
		});

		return $config;
	}
}
