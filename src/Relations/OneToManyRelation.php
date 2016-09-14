<?php

namespace Just\Shapeshifter\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Routing\RouteCollection;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\Core\Controllers\AdminController;
use Just\Shapeshifter\Exceptions\ShapeShifterException;
use Request;
use Route;
use View;

class OneToManyRelation extends Attribute
{
    /**
     * @var AdminController
     */
    protected $fromcontroller;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var AdminController
     */
    protected $destination;

    /**
     * @var string
     */
    protected $function;

    /**
     * @param AdminController $fromController
     * @param string          $destination
     * @param string          $function
     * @param array           $flags
     */
    public function __construct(AdminController $fromController, $destination, $function, $flags = [])
    {
        $this->destination    = 'admin.' . $destination . '.index';
        $this->fromcontroller = $fromController;
        $this->model          = $fromController->getRepository()->getModel();
        $this->function       = $function;
        $this->name           = $destination;

        $this->flags   = $flags;
        $this->flags[] = 'hide_list';
    }

    /**
     * display
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     *
     * @throws \Just\Shapeshifter\Exceptions\ShapeShifterException
     */
    public function compile(Model $model = null)
    {
        if ($this->fromcontroller->getMode() !== 'edit') {
            return;
        }

        $routes     = Route::getRoutes();
        $controller = $this->resolveControllerByName($routes);

        $node     = explode('.', $this->destination, -1);
        $path     = explode('/', Request::path(), -1);
        $numerics = array_filter($path, 'is_numeric');

        $path = array_filter($path, function ($val) {
            return !is_numeric($val);
        });

        return View::make('shapeshifter::relations.OneToManyRelation', [
            'route'    => $this->getDestinationRoute($path, $node, $numerics),
            'title'    => str_replace('_', ' ', $controller->getTitle()),
            'function' => $this->function,
        ])->render();
    }

    /**
     * @param Model $model
     *
     * @return null
     */
    public function getSaveValue(Model $model)
    {
        return;
    }

    /**
     * @return mixed
     */
    public function getDestinationName()
    {
        return $this->destination->getTitle();
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param RouteCollection $routes
     *
     * @return Controller
     *
     * @throws ShapeShifterException
     */
    protected function resolveControllerByName(RouteCollection $routes)
    {
        $contr = $routes->getByName($this->destination);

        if (!$contr) {
            throw new ShapeShifterException("Route [{$this->destination}] does not exist");
        }

        list($class) = explode('@', $contr->getActionName());

        return app($class);
    }

    /**
     * @param $path
     * @param $node
     * @param $numerics
     *
     * @return string
     *
     * @throws ShapeShifterException
     */
    private function getDestinationRoute($path, $node, $numerics)
    {
        $route = route(implode('.', array_merge($path, [last($node), 'index'])), $numerics);

        if (str_contains($route, '?')) {
            throw new ShapeShifterException('The named route is already defined');
        }

        return $route;
    }
}
