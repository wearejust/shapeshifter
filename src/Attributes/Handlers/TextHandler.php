<?php

namespace Just\Shapeshifter\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\View\AttributeView;

class TextHandler extends Handler
{
    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @param \Just\Shapeshifter\Attributes\Attribute  $attribute
     *
     * @return mixed
     */
    public function view(Model $model, Attribute $attribute)
    {
        return new AttributeView('Text', [
            'model' => $model,
            'name' => $attribute->getName(),
            'type' => $attribute->getType(),
            'flags' => $attribute->getFlags()
        ]);
    }
}
