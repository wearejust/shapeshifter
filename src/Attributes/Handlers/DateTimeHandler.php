<?php

namespace Just\Shapeshifter\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\View\AttributeView;

class DateTimeHandler extends DateHandler
{
    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param \Just\Shapeshifter\Attributes\Attribute  $attribute
     *
     * @return mixed
     */
    public function view(Model $model, Attribute $attribute)
    {
        return new AttributeView('DateTime', [
            'model' => $model,
            'name' => $attribute->getName(),
            'flags' => $attribute->getFlags(),
        ]);
    }
}
