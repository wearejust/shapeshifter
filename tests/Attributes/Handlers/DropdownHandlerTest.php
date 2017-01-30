<?php

namespace Just\Shapeshifter\Test\Attributes\Handlers;

use Just\Shapeshifter\Attributes\Dropdown;
use Just\Shapeshifter\Attributes\Handlers\DropdownHandler;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class DropdownHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->model = new TestModel(['name' => 'key']);

    }

    /**
     *
     * @test
     */
    public function attribute_gets_selected_item()
    {
        $model = new TestModel(['option' => 'key']);
        $handler = new DropdownHandler();
        $attribute = new Dropdown('option', ['key33' => 'Option 1', 'key' => 'Option 2', 'key3' => 'Option 3']);
        $this->assertSame('Option 2', $handler->getDisplayValue($model, $attribute));

        $attribute = new Dropdown('option_doesnt_exist', ['Option 1', 'Option 2', 'Option 3']);
        $this->assertFalse($handler->getDisplayValue($model, $attribute));
    }

    /** @test */
    public function attribute_right_view()
    {
        $attribute = new Dropdown('name', ['Option 1', 'Option 2']);
        $handler = new DropdownHandler();

        $this->assertInstanceOf(AttributeView::class, $handler->view($this->model, $attribute));
    }
}
