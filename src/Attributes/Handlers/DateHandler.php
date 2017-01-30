<?php

namespace Just\Shapeshifter\Attributes\Handlers;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\View\AttributeView;

class DateHandler extends Handler
{
    /**
     * Returns the date in the dutch format. If no date is passed, nothing is returned.
     *
     * @param Model                                   $model
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model, Attribute $attribute)
    {
        if (! $model->{$attribute->getName()}) {
            return null;
        }

        return new DateTime($model->{$attribute->getName()});
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model     $model
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return mixed
     */
    public function getEditValue(Model $model, Attribute $attribute)
    {
        return $this->getDisplayValue($model, $attribute);
    }

    /**
     * Converts an dutch date string to an DateTime string (for the Database)
     *
     * @param Model                                   $model
     * @param                                         $value
     * @param \Just\Shapeshifter\Attributes\Attribute $attribute
     *
     * @return mixed Value.
     */
    public function setModelValue(Model $model, $value, Attribute $attribute)
    {
        if ($value instanceof DateTime) {
            $model->{$attribute->getName()} = $value;
        }else {
            $model->{$attribute->getName()} = $value ? new DateTime($value) : null;
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param \Just\Shapeshifter\Attributes\Attribute  $attribute
     *
     * @return mixed
     */
    public function view(Model $model, Attribute $attribute)
    {
        return new AttributeView('Date', [
            'model' => $model,
            'name' => $attribute->getName(),
            'flags' => $attribute->getFlags(),
        ]);
    }
}
