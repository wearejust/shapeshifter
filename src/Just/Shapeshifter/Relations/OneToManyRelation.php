<?php namespace Just\Shapeshifter\Relations;

use Just\Shapeshifter\Controllers as Controller;
use Just\Shapeshifter\ShapeShifterException;
use Request;
use Route;
use View;

/**
* OneToManyRelation
*
* @uses     Relation
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class OneToManyRelation extends Relation
{
    /**
     * $fromcontroller
     *
     * @var mixed
     *
     * @access protected
     */
	protected $fromcontroller;

    /**
     * $model
     *
     * @var mixed
     *
     * @access protected
     */
	protected $model;

    /**
     * $destination
     *
     * @var mixed
     *
     * @access protected
     */
	protected $destination;

    /**
     * $function
     *
     * @var mixed
     *
     * @access protected
     */
	protected $function;


    /**
     * @param string $fromcontroller
     * @param array $destination
     * @param $function
     * @param array $flags
     */
    public function __construct($fromcontroller, $destination, $function, $flags = array())
	{
        $this->destination = 'admin.' . $destination . '.index';
		$this->fromcontroller = $fromcontroller;
		$this->model = $fromcontroller->repo->getModel();
		$this->function = $function;
		$this->name = $destination;

		$this->flags = $flags;
		$this->flags[] = 'hide_list';		
	}


    /**
     * display
     *
     * @access public
     * @throws \Just\Shapeshifter\ShapeShifterException
     * @return mixed Value.
     */
	public function compile()
	{
        if ($this->fromcontroller->mode !== 'edit') return null;

        $routes = Route::getRoutes();
        $controller = $this->resolveControllerByName($routes);

        $node = explode('.', $this->destination, -1);
        $path = explode('/', Request::path(), -1);
        $numerics = array_filter($path, 'is_numeric');

        $path = array_filter($path, function($val) {
            return ! is_numeric($val);
        });

        $this->html = View::make('shapeshifter::relations.OneToManyRelation', array(
            'route' => $this->getDestinationRoute($path, $node, $numerics),
            'title' => $controller->getTitle(),
            'function' => $this->function
        ))->render();
    }

    /**
     * @return null
     */
    public function getSaveValue()
    {
        return null;
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
     * @param $routes
     * @throws \Just\Shapeshifter\ShapeShifterException
     * @return mixed
     */
    protected function resolveControllerByName($routes)
    {
        $contr = $routes->getByName($this->destination);

        if ( ! $contr)
        {
            throw new ShapeShifterException("Route [{$this->destination}] does not exist");
        }

        $action = $contr->getActionName();
        $action = head(explode('@', $action));

        return \App::make($action);
    }

    /**
     * @param $path
     * @param $node
     * @param $numerics
     * @throws \Just\Shapeshifter\ShapeShifterException
     * @return string
     */
    private function getDestinationRoute($path, $node, $numerics)
    {
        $route = route(implode('.', array_merge($path, array(last($node), 'index'))), $numerics);

        if (str_contains($route, '?'))
        {
            throw new ShapeShifterException('The named route is already defined');
        }

        return $route;
    }

}

?>
