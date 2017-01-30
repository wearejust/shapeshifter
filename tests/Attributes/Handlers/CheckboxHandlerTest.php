<?php

namespace Just\Shapeshifter\Test\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Attributes\Checkbox;
use Just\Shapeshifter\Attributes\Handlers\CheckboxHandler;
use Just\Shapeshifter\Test\Stubs\TestAttribute;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class CheckboxHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->attribute = new Checkbox('name');
        $this->handler = new CheckboxHandler();
        $this->model = new TestModel(['name' => 0]);

    }

    /** @test */
    public function attribute_get_display_data_returns_always_boolean()
    {
        $this->handler->setModelValue($this->model, '1', $this->attribute);
        $this->assertTrue($this->model->name);
        $this->handler->setModelValue($this->model, 'imastring', $this->attribute);
        $this->assertTrue($this->model->name);

        $this->handler->setModelValue($this->model, '', $this->attribute);
        $this->assertFalse($this->model->name);
        $this->handler->setModelValue($this->model, '0', $this->attribute);
        $this->assertFalse($this->model->name);
    }


    /** @test */
    public function attribute_right_view()
    {
        $this->assertInstanceOf(AttributeView::class, $this->handler->view($this->model, $this->attribute));
    }
}
