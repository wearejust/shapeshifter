<?php

namespace Just\Shapeshifter\Attributes\Collections;

use Closure;
use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;

class Tab extends Collection
{
    /**
     * @param Attribute $attribute
     *
     * @return $this
     */
    public function add(Attribute $attribute)
    {
        return $this->section('default', function(Section $section) use ($attribute) {
            return $section->add($attribute);
        });
    }

    /**
     * @param          $section
     * @param \Closure $closure
     *
     * @return $this
     */
    public function section($section, Closure $closure)
    {
        $collection = $this->get($section, new Section());

        $closure($collection);

        return $this->put($section, $collection);
    }

}
