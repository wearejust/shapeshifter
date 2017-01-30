<?php

namespace Just\Shapeshifter\Attributes\Collections;

use Closure;
use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;

class AttributeCollection extends Collection
{
    /**
     * @param Attribute $attribute
     *
     * @return $this
     */
    public function add(Attribute $attribute)
    {
        return $this->addToTab('default', 'default', function(Section $section) use ($attribute) {
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
        return $this->addToTab('default', $section, $closure);
    }

    /**
     * @param string $tabName
     * @param string $sectionName
     * @param \Closure $closure
     *
     * @return AttributeCollection
     */
    private function addToTab($tabName, $sectionName, Closure $closure)
    {
        return $this->tab($tabName, function(Tab $tab) use ($sectionName, $closure) {
            return $tab->section($sectionName, $closure);
        });
    }

    /**
     * @param          $tab
     * @param \Closure $closure
     *
     * @return $this
     */
    public function tab($tab, Closure $closure)
    {
        $collection = $this->get($tab, new Tab());

        $closure($collection);

        return $this->put($tab, $collection);
    }
}
