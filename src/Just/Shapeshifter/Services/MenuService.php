<?php namespace Just\Shapeshifter\Services;

use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class MenuService
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Application $app, Collection $collection)
    {
        $this->app = $app;
        $this->collection = $collection;
    }

    public function generateMenu()
    {
        $config = $this->collection->make($this->app['config']->get('shapeshifter::config.menu'));

        $config->transform(function($config)
        {
            $config['active'] = $this->app['request']->segment(2) === $config['url'];

            $config['children'] = $this->collection->make($config['children']);
            $config['children']->transform(function($child) use (&$config)
            {
                $child['active'] = $this->app['request']->segment(2) === $child['url'];
                if ($child['active']) {
                    $config['active'] = true;
                }

                return $child;
            });

            return $config;
        });

        return $config;
    }
}
