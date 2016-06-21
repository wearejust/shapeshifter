<?php

namespace Just\Shapeshifter\Core\Controllers;

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
}
