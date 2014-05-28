<?php namespace Just\Shapeshifter\Services;

use Config;
use Illuminate\Support\Collection;
use Request;

class MenuService {

    public function generateMenu()
    {
        $config = Collection::make(Config::get('shapeshifter::config.menu'));

        $config->transform(function($config)
        {
            $config['active'] = Request::segment(2) === $config['url'];

            $config['children'] = Collection::make($config['children']);
            $config['children']->transform(function($child) use (&$config)
            {
                $child['active'] = Request::segment(2) === $child['url'];
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
