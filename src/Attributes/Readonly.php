<?php

namespace Just\Shapeshifter\Attributes;

class Readonly extends Attribute
{
    /**
     * @return string
     */
    public function getHandler()
    {
        return Handlers\ReadonlyHandler::class;
    }
}
