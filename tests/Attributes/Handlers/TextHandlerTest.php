<?php

namespace Just\Shapeshifter\Test\Attributes;

use Just\Shapeshifter\Attributes\Handlers\TextHandler;
use Just\Shapeshifter\Attributes\Text;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class TextHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->attribute = new Text('name');
        $this->model = new TestModel(['name' => 'value']);

    }

    /** @test */
    public function attribute_right_view()
    {
        $handler = new TextHandler();

        $this->assertInstanceOf(AttributeView::class, $handler->view($this->model, $this->attribute));
    }
}
