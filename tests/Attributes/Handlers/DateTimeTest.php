<?php

namespace Just\Shapeshifter\Test\Attributes\Handlers;

use Just\Shapeshifter\Attributes\DateTime;
use Just\Shapeshifter\Attributes\Handlers\DateTimeHandler;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->handler = new DateTimeHandler();
        $this->attribute = new DateTime('name');
        $this->model = new TestModel();

    }

    /** @test */
    public function attribute_right_view()
    {
        $this->assertInstanceOf(AttributeView::class, $this->handler->view($this->model, $this->attribute));
    }
}
