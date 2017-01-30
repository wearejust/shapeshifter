<?php

namespace Just\Shapeshifter\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Just\Shapeshifter\Attributes\Collections\AttributeCollection;

abstract class AdminController extends Controller
{
    /**
     * @param Model $model
     *
     * @return Collection
     */
    abstract protected function indexQuery(Model $model);

    /**
     * @param AttributeCollection $collection
     *
     * @return AttributeCollection
     */
    abstract protected function components(AttributeCollection $collection);

    /**
     * @return Collection
     */
    public function index()
    {
        $model = new $this->model;

        return [
            'models' => $this->indexQuery($model),
            'components' => $this->components(new AttributeCollection())
        ];
    }

    public function create()
    {

    }
}
