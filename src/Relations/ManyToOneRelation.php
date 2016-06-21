<?php

namespace Just\Shapeshifter\Relations;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Core\Controllers\AdminController;
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
     * @param AdminController $fromController
     * @param array           $destination
     * @param $function
     * @param array $flags
     *
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     */
    public function __construct(AdminController $fromController, $destination, $function, $flags = [])
    {
        $this->destination    = 'admin.' . $destination . '.index';
        $this->destination    = $this->setupDestination();
        $this->fromcontroller = $fromController;
        $this->model          = $fromController->getRepo()->getModel();
        $this->function       = $function;
        $this->name           = $destination;
        $this->flags          = $flags;
        $this->foreign        = $this->name        = $this->getForeignField();
    }

    public function getDisplayValue(Model $model)
    {
        $destination = $this->destination->getModel();
        $destination = new $destination();
        $destination = $destination->find($model->{$this->name});

        if ($destination) {
            return $destination->{$this->destination->getDescriptor()};
        } else {
            return '';
        }
    }

    /**
     * display
     *
     * @access public
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     */
    public function compile(Model $model = null)
    {
        return View::make('shapeshifter::relations.ManyToOneRelation', [
            'name'   => $this->foreign,
            'label'  => $this->getLabel($this->foreign),
            'select' => $this->getValuesForSelect(),
        ])->render();
    }

    /**
     * @return mixed
     *
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     */
    public function getForeignField()
    {
        $model = $this->fromcontroller->getRepo()->getModel();

        if (! method_exists($model, $this->function)) {
            $modelName = get_class($model);

            throw new MethodNotExistException("Relation method [{$this->function}] doest not exist on [{$modelName}] model");
        }

        return $model->{$this->function}()->getForeignKey();
    }

    /**
     * @param Model $model
     *
     * @return string
     */
    public function getSaveValue(Model $model)
    {
        if (! $this->value) {
            $field = $this->getForeignField();
            $model->{$field} = null;
        } else {
            $associate = $this->getDestination()->getRepo()->getModel()->findOrFail($this->value);
            $model->{$this->function}()->associate($associate);
        }
    }

    /**
     * @return mixed
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

        $values = $this->destination->getRepo()->listed($descriptor, 'id')->toArray();
        if (! $this->required) {
            $values = ['' => ''] + $values;
        }

        return $values;
    }
}
