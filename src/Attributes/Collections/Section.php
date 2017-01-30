<?php

namespace Just\Shapeshifter\Attributes\Collections;

use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;

class Section extends Collection
{
    /**
     * @param Attribute $attribute
     *
     * @return $this
     */
    public function add(Attribute $attribute)
    {
        return $this->put($attribute->getName(), $attribute);
    }
}

