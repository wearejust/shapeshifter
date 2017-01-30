<?php

namespace Just\Shapeshifter\Test\Attributes\Collections;


use Just\Shapeshifter\Attributes\Collections\Section;
use Just\Shapeshifter\Attributes\Collections\Tab;
use Just\Shapeshifter\Test\Stubs\TestAttribute;

class TabTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_add_section_to_tab()
    {
        $collection = new Tab();
        $collection->section('sectionName', function($c) {
            $c->add(new TestAttribute('testcees'));
            $c->add(new TestAttribute('testcees2'));
            $c->add(new TestAttribute('testcees3'));
        });

        $firstKey = null;
        $collection->first(function($value, $key) use (&$firstKey) {
            $firstKey = $key;
            return true;
        });

        $this->assertSame('sectionName', $firstKey);
        $this->assertCount(1, $collection);
        $this->assertCount(3, $collection->get('sectionName'));
        $this->assertInstanceOf(Section::class, $collection->first());
    }
}
