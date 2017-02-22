<?php

namespace Just\Shapeshifter\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use InvalidArgumentException;
use Just\Shapeshifter\Attributes\Collections\ComponentCollection;

abstract class AdminController extends Controller
{
    /**
     * @param ComponentCollection $collection
     *
     * @return ComponentCollection
     */
    abstract protected function components(ComponentCollection $collection) : ComponentCollection;

    /**
     * @param Model $model
     *
     * @return Collection
     */
    protected function indexQuery(Model $model) : Collection
    {
        return $model->get();
    }

    /**
     * @return array|Collection
     */
    public function index() : array
    {
        $model = new $this->model;
        $components = $this->components(new ComponentCollection);

        return [
            'models' => $this->indexQuery($model),
            'components' => $components,
            'columns' => $this->getActiveColumns($components),
        ];
    }

    /**
     * @return array
     */
    abstract protected function columns() : array;

    /**
     * @param ComponentCollection $components
     * @return array
     */
    private function getActiveColumns(ComponentCollection $components) : array
    {
        $availableComponents = $components->flatten()->map(function ($item) {
            return $item->getName();
        })->toArray();

        $unrecognized = array_diff($this->columns(), $availableComponents);

        if (count($unrecognized) > 0) {
            throw new InvalidArgumentException(sprintf(
                'The key(s) [%s] is not recognized as component(s).',
                implode(', ', $unrecognized)
            ));
        }

        return $this->columns();
    }
}
