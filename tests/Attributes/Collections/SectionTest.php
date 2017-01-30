<?php

namespace Just\Shapeshifter\Test\Attributes\Collections;

use Just\Shapeshifter\Attributes\Collections\Section;
use Just\Shapeshifter\Test\Stubs\TestAttribute;

class SectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_add_attributes_to_section()
    {
        $collection = new Section();
        $collection->add(new TestAttribute('testcees'));
        $this->assertCount(1, $collection);
    }
}
