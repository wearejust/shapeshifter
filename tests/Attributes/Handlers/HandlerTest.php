<?php

namespace Just\Shapeshifter\Test\Attributes\Handlers;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Test\Stubs\TestAttribute;
use Just\Shapeshifter\Test\Stubs\TestHandler;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $abstract;
    protected $model;

    public function setUp()
    {
        $this->model = new TestModel();

    }

    /** @test */
    public function compile_method_return_right_thing()
    {
        $abstract = new TestHandler();
        $attribute = new TestAttribute('name');

        $this->assertInstanceOf(AttributeView::class, $abstract->compile($this->model, $attribute));
    }

    /** @test */
    public function setvalue_test()
    {
        $abstract = new TestHandler();
        $attribute = new TestAttribute('name');

        $model = new TestModel(['name' => 'value']);
        $abstract->getEditValue($model, $attribute);

        $this->assertSame('value', $model->name);
        $this->assertNull($model->dont_exist_field);

        $abstract->getDisplayValue($model, $attribute);

        $this->assertSame('value', $model->name);
        $this->assertNull($model->dont_exist_field);
    }

    /** @test */
    public function set_model_value()
    {
        $abstract = new TestHandler();
        $attribute = new TestAttribute('name');
        $model = new TestModel(['name' => 'value']);

        $model = $abstract->setModelValue($model, 'new_value', $attribute);

        $this->assertInstanceOf(Model::class, $model);
        $this->assertSame('new_value', $model->name);
    }
}
