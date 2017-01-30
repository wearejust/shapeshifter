<?php

namespace Just\Shapeshifter\Test\Attributes;

use Just\Shapeshifter\Attributes\Dropdown;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class DropdownTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->model = new TestModel(['name' => 'key']);

    }

    /** @test */
    public function attribute_constructs_properly()
    {
        $attribute = new Dropdown('name', ['Option 1', 'Option 2', 'Option 3']);
        $this->assertEquals(3, count($attribute->getOptions()));
        foreach($attribute->getOptions() as $key => $option) {
            $this->assertSame($key, $option);
        }

        $attribute = new Dropdown('name', ['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3']);
        foreach($attribute->getOptions() as $key => $option) {
            $this->assertNotSame($key, $option);
        }
    }


    /**
     * @test
     * @expectedException \Just\Shapeshifter\Exceptions\NoOptionsProvided
     */
    public function attribute_throws_exception_when_no_options_are_passed()
    {
        new Dropdown('name', []);
    }
}
