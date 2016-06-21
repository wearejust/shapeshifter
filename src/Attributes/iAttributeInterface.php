<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;

interface iAttributeInterface
{
    /**
     * @param $value
     * @param $oldValue
     *
     * @return mixed
     */
    public function setAttributeValue($value, $oldValue);

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function getDisplayValue(Model $model);

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function getEditValue(Model $model);

    /**
     * @param Model $model
     *
     * @return Model
     */
    public function getSaveValue(Model $model);

    /**
     * @param Model|null $model
     *
     * @return mixed
     */
    public function compile(Model $model = null);
}
