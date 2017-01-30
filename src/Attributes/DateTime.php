<?php

namespace Just\Shapeshifter\Attributes;

class DateTime extends Attribute
{
    /**
     * @return string
     */
    public function getHandler()
    {
        return Handlers\DateTimeHandler::class;
    }
}
