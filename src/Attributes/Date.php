<?php

namespace Just\Shapeshifter\Attributes;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\View\AttributeView;

class Date extends Attribute
{
    /**
     * @return string
     */
    public function getHandler()
    {
        return Handlers\DateHandler::class;
    }
}
