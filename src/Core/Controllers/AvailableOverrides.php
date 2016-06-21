<?php

namespace Just\Shapeshifter\Core\Controllers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Form\Form;

trait AvailableOverrides
{
    /**
     * Trigger is fired before an new record is saved to the database
     *
     * @param $model
     *
     * @return Model
     */
    public function beforeAdd(Model $model)
    {
        return $model;
    }

    /**
     * Trigger is fired after an new record is saved to the database
     *
     * @param $model
     *
     * @return Model
     */
    public function afterAdd(Model $model)
    {
        return $model;
    }

    /**
     * Trigger is fired before an new record is updated to the database
     *
     * @param $model
     *
     * @return Model
     */
    public function beforeUpdate(Model $model)
    {
        return $model;
    }

    /**
     * Trigger is fired after an record is updated to the database
     *
     * @param $model
     *
     * @return Model
     */
    public function afterUpdate(Model $model)
    {
        return $model;
    }

    /**
     * Trigger is fired before an record will be deleted
     *
     * @param $model
     *
     * @return Model
     */
    public function beforeDestroy(Model $model)
    {
        return $model;
    }

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
     * @param Form $modifier
     *
     * @return Form
     */
    protected function beforeInit(Form $modifier)
    {
        return $modifier;
    }

    /**
     * @param Form $modifier
     *
     * @return Form
     */
    protected function afterInit(Form $modifier)
    {
        return $modifier;
    }

    /**
     * @param AdminController $node
     *
     * @return AdminController
     */
    public function beforeRender(AdminController $node)
    {
        return $node;
    }
}
