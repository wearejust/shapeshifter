<?php

namespace Just\Shapeshifter\Services;

use Cartalyst\Sentinel\Sentinel;
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
     * @var \Cartalyst\Sentinel\Sentinel
     */
    private $auth;

    /**
     * @param Application                  $app
     * @param Collection                   $collection
     * @param Module                       $modules
     * @param \Cartalyst\Sentinel\Sentinel $auth
     */
    public function __construct(Application $app, Collection $collection, Module $modules, Sentinel $auth)
    {
        $this->app        = $app;
        $this->collection = $collection;
        $this->modules    = $modules;
        $this->auth       = $auth;
    }

    /**
     * @return Collection
     */
    public function generateMenu()
    {
        $menu = new Collection();

        if (! $this->auth->getUser()) {
            return $menu;
        }

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

        return $menu
            ->filter(function($item) {
                return $this->hasAccessToRoute($item['route']);
            })
            ->map(function($item) {
                $item['children'] = array_filter($item['children'], function($item) {
                    return $this->hasAccessToRoute($item['route']);
                });

                return $item;
            })
            ->filter(function($item) {
               return $this->hasAccessToRoute('superuser') || (count($item['children']) === 0 && $item['route'] !== null);
            });
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
            $url    = $this->getFullRoute($menuItem);
            $name   = array_get($menuItem, 'name');
            $route  = array_get($menuItem, 'route');

            $active = false;
            if (! $itemActive && str_is($url . '*', '/' . $this->app['request']->path())) {
                $itemActive = true;
                $active = true;
            }

            return compact('name', 'url', 'active', 'route');
        }, $children);

        return [
            'active'   => $itemActive,
            'name'     => $item['name'],
            'url'      => $url,
            'icon'     => $item['icon'],
            'route'    => $item['route'],
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

    /**
     * @param $route
     *
     * @return bool
     */
    private function hasAccessToRoute($route)
    {
        return ! config('shapeshifter.menu_strict_auth_checking') || $route === null || $this->auth->getUser()->hasAnyAccess($route, 'superuser');
    }
}
