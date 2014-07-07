<?php namespace Just\Shapeshifter\Relations;

use Just\Shapeshifter\Controllers as Controller;
use Just\Shapeshifter\Exceptions\MethodNotExistException;
use Route;
use View;

class ManyToOneRelation extends OneToManyRelation
{

    /**
     * @var mixed
     */
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
        $this->destination = 'admin.' . $destination . '.index';
        $this->destination = $this->setupDestination();
        $this->fromcontroller = $fromcontroller;
        $this->model = $fromcontroller->repo->getModel();
        $this->function = $function;
        $this->name = $destination;
        $this->flags = $flags;
        $this->foreign = $this->name = $this->getForeignField();
    }


    public function getDisplayValue()
    {
        $model = $this->destination->getModel();
        $model = new $model;
        $model = $model->find($this->value);
        if ( $model)
        {
            return $model->{$this->destination->getDescriptor()};
        }
        else
        {
            return '';
        }

    }


    /**
     * display
     *
     * @access public
     * @return mixed Value.
     */
    public function compile()
    {
        $this->html = View::make('shapeshifter::relations.ManyToOneRelation', array(
            'name' => $this->foreign,
            'label' => $this->getLabel($this->foreign),
            'select' => $this->getValuesForSelect(),
        ))->render();
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
