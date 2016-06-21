<?php

namespace Just\Shapeshifter\Attributes;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class DateTimeAttribute extends DateAttribute implements iAttributeInterface
{
    /**
     * Returns the date in the dutch format. If no date is passed, nothing is returned.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        try {
            $date = new DateTime($model->{$this->name});

            return $date->format('d-m-Y H:i:s');
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Converts an dutch date string to an DateTime string (for the Database)
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function getSaveValue(Model $model)
    {
        $model->{$this->name} = $this->value
            ? date('d-m-Y H:i:s', strtotime($this->value))
            : null;
    }
}
