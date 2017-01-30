<?php

namespace Just\Shapeshifter\Test\Stubs;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\Attributes\Handlers\Handler;
use Just\Shapeshifter\View\AttributeView;

class TestHandler extends Handler
{
    /**
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return mixed
     */
    public function view(Model $model, Attribute $attribute)
    {
        return new AttributeView('test', []);
    }
}
