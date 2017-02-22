<?php

namespace Just\Shapeshifter\Test\Attributes\Collections;

use Just\Shapeshifter\Attributes\Collections\ComponentCollection;
use Just\Shapeshifter\Attributes\Collections\Tab;
use Just\Shapeshifter\Test\Stubs\TestAttribute;

class ComponentCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_add_tabs_to_collection()
    {
        $collection = new ComponentCollection();
        $collection->tab('tabName', function($c) {
            $c->add(new TestAttribute('testcees'));
        });

        $firstKey = null;
        $collection->first(function($value, $key) use (&$firstKey) {
            $firstKey = $key;
            return true;
        });

        $this->assertSame('tabName', $firstKey);
        $this->assertCount(1, $collection);
        $this->assertInstanceOf(Tab::class, $collection->first());
    }
}
