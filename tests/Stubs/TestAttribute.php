<?php

namespace Just\Shapeshifter\Test\Stubs;

use Just\Shapeshifter\Attributes\Attribute;

class TestAttribute extends Attribute
{
    /**
     * @return string
     */
    public function getHandler()
    {
        return TestHandler::class;
    }
}
