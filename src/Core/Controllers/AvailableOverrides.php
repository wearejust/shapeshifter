<?php

namespace Just\Shapeshifter\Core\Controllers;

use Illuminate\Database\Eloquent\Model;

trait AvailableOverrides
{
     /**
     * @param $route
     * @param $args
     * @param $currentId
     *
     * @return mixed
     */
    protected function redirectAfterUpdate($route, $args, $currentId)
    {
        return redirect()->route($route, $args);
    }

    /**
     * @param $route
     * @param $args
     * @param $currentId
     *
     * @return mixed
     */
    protected function redirectAfterStore($route, $args, $currentId)
    {
        return redirect()->route($route, $args);
    }

    /**
     * @param $route
     * @param $args
     *
     * @return mixed
     */
    protected function redirectAfterDestroy($route, $args)
    {
        return redirect()->route($route, $args);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function afterAdd(Model $model)
    {
        return $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function afterUpdate(Model $model)
    {
        return $model;
    }
}
