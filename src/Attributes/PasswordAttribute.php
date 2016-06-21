<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;

class PasswordAttribute extends Attribute implements iAttributeInterface
{
    /**
     * getEditValue
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     */
    public function getEditValue(Model $model)
    {
        return '';
    }

    /**
     * getDisplayValue
     *
     * @param Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        return '******';
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function getSaveValue(Model $model)
    {
        $model->{$this->name} = $this->value ?: null;
    }
}
