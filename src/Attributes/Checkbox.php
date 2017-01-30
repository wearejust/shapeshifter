<?php

namespace Just\Shapeshifter\Attributes;

class Checkbox extends Attribute
{
    /**
     * @return string
     */
    public function getHandler()
    {
        return Handlers\CheckboxHandler::class;
    }
}
