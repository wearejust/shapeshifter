<?php

namespace Just\Shapeshifter\Test\Attributes;

use Just\Shapeshifter\Attributes\Handlers\ReadonlyHandler;
use Just\Shapeshifter\Attributes\Readonly;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class ReadonlyHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->attribute = new Readonly('name');
        $this->model = new TestModel(['name' => 'value']);

    }

    /** @test */
    public function attribute_modifies_no_data_on_model()
    {
        $handler = new ReadonlyHandler();
        $handler->setModelValue($this->model, 'new_value', $this->attribute);

        $this->assertSame('value', $this->model->name);
    }

    /** @test */
    public function attribute_right_view()
    {
        $handler = new ReadonlyHandler();

        $this->assertInstanceOf(AttributeView::class, $handler->view($this->model, $this->attribute));
    }
}
