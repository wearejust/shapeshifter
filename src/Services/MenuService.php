<?php

namespace Just\Shapeshifter\Services;

use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Pingpong\Modules\Contracts\RepositoryInterface as Module;

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

    /**
     * @var Module
     */
    private $modules;

    /**
     * @param Application $app
     * @param Collection  $collection
     * @param Module      $modules
     */
    public function __construct(Application $app, Collection $collection, Module $modules)
    {
        $this->app        = $app;
        $this->collection = $collection;
        $this->modules    = $modules;
    }

    /**
     * @return Collection
     */
    public function generateMenu()
    {
        $menu = new Collection();

        if (config('shapeshifter.menu')) {
            foreach (config('shapeshifter.menu') as $item) {
                $item = $this->parseItem($item);
                $menu->push($item);
            }
        } else {
            foreach ($this->modules->getOrdered() as $module) {
                $attributes = $module->json()->getAttributes();
                $item       = $this->parseItem($attributes);
                $menu->push($item);
            }
        }

        return $menu;
    }

    /**
     * @param $item
     *
     * @return array
     */
    private function parseItem($item)
    {
        $itemActive = $this->app['router']->is($item['route']);

        $children = array_get($item, 'children', []);
        $url      = count($children) ? '#' : $this->getFullRoute($item);

        $children = array_map(function ($menuItem) use (&$itemActive) {

            $url        = $this->getFullRoute($menuItem);
            $name       = array_get($menuItem, 'name');

            $active = false;
            if (! $itemActive && str_is($url . '*', '/' . $this->app['request']->path())) {
                $itemActive = true;
                $active = true;
            }

            return compact('name', 'url', 'active');

        }, $children);

        return [
            'active'   => $itemActive,
            'name'     => $item['name'],
            'url'      => $url,
            'icon'     => $item['icon'],
            'children' => $children
        ];
    }

    /**
     * @param $menuItem
     *
     * @return mixed
     */
    private function getFullRoute($menuItem)
    {
        $parameters = array_get($menuItem, 'parameters', []);
        if (is_callable($parameters)) {
            $parameters = $parameters();
        }

        return route($menuItem['route'], $parameters, false);
    }
}
