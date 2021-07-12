<?php

namespace Just\Shapeshifter\Core\Composer;

use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Just\Shapeshifter\Services\BreadcrumbService;
use Just\Shapeshifter\Services\MenuService;

class Layout
{
    /**
     * @var MenuService
     */
    private $menuService;

    /**
     * @var Sentinel
     */
    private $sentinel;

    /**
     * @var BreadcrumbService
     */
    private $breadcrumbService;

    /**
     * @param MenuService       $menuService
     * @param Sentinel          $sentinel
     * @param BreadcrumbService $breadcrumbService
     */
    public function __construct(MenuService $menuService, Sentinel $sentinel, BreadcrumbService $breadcrumbService)
    {
        $this->menuService       = $menuService;
        $this->sentinel          = $sentinel;
        $this->breadcrumbService = $breadcrumbService;
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('currentUser',  $this->sentinel->getUser());
        $view->with('mode', Arr::get($view->getData(), 'mode', ''));
        $view->with('breadcrumbs', config('shapeshifter.breadcrumbs') ? $this->breadcrumbService->breadcrumbs() : []);
        $view->with('menu', $this->menuService->generateMenu());
    }
}
