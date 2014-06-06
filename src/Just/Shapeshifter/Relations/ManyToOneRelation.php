<?php namespace Just\Shapeshifter\Relations;

use Just\Shapeshifter\Controllers as Controller;
use Just\Shapeshifter\Exceptions\MethodNotExistException;
use Route;
use View;

class ManyToOneRelation extends OneToManyRelation
{
    protected $foreign;

    /**
     * @param string $fromcontroller
     * @param array $destination
     * @param $function
     * @param array $flags
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     */
    public function __construct($fromcontroller, $destination, $function, $flags = array())
    {
        parent::__construct($fromcontroller, $destination, $function, $flags);

        $this->destination = $this->setupDestination();
        $this->foreign = $this->name = $this->getForeignField();
    }

    /**
     * display
     * 
     * @access public
     * @return mixed Value.
     */
	public function display()
	{
        $name = $this->foreign;
        $select = $this->getValuesForSelect();
        $label = $this->getLabel($name);

        return View::make('shapeshifter::relations.ManyToOneRelation', compact('name', 'label', 'select'));
	}

    /**
     * @return mixed
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     */
    public function getForeignField()
	{
        $model = $this->fromcontroller->repo->getModel();

        if (! method_exists($model, $this->function))
        {
            $modelName = get_class($model);

            throw new MethodNotExistException("Relation method [{$this->function}] doest not exist on [{$modelName}] model");
        }

        return $model->{$this->function}()->getForeignKey();
	}

    /**
     * @return string
     */
    public function getSaveValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     * @throws \Just\Shapeshifter\ShapeShifterException
     */
    protected function setupDestination()
    {
        $routes = Route::getRoutes();

        return $this->resolveControllerByName($routes);
    }

    /**
     * @return array
     */
    private function getValuesForSelect()
    {
        $descriptor = $this->destination->getDescriptor();

        return array('' => '') + $this->destination->repo->listed($descriptor, 'id');
    }
}

?>
