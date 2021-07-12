<?php

namespace Just\Shapeshifter\Attributes;

use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;

class DateAttribute extends Attribute implements iAttributeInterface
{
    /**
     * Returns the date in the dutch format. If no date is passed, nothing is returned.
     *
     * @param Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        if (! $model->{$this->name}) {
            return '';
        }
        
        try {
            $date = new DateTime($model->{$this->name});

            return $date->format('Y-m-d');
        } catch (Exception) {
            return '';
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     */
    public function getEditValue(Model $model)
    {
        return $this->getDisplayValue($model);
    }

    /**
     * Converts an dutch date string to an DateTime string (for the Database)
     *
     * @param Model $model
     *
     * @return mixed Value.
     */
    public function getSaveValue(Model $model)
    {
        if (! $this->value) {
            $model->{$this->name} = null;
        } else {
            $date = new DateTime($this->value);

            $model->{$this->name} = $date->format('Y-m-d');
        }
    }
}
