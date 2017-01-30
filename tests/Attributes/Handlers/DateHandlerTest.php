<?php

namespace Just\Shapeshifter\Test\Attributes\Handlers;

use Just\Shapeshifter\Attributes\Date;
use Just\Shapeshifter\Attributes\Handlers\DateHandler;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class DateTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $model;

    public function setUp()
    {
        $this->handler = new DateHandler();
        $this->attribute = new Date('name');
        $this->model = new TestModel();

    }


    /** @test */
    public function attribute_get_attribute_value()
    {
        $this->assertNull($this->handler->getDisplayValue($this->model, $this->attribute));

        $model = new TestModel(['name' => '1990-08-08']);
        $this->assertInstanceOf('DateTime', $this->handler->getDisplayValue($model, $this->attribute));
    }

    /** @test */
    public function attribute_returns_null_or_datetime()
    {
        $this->handler->setModelValue($this->model, '1990-08-08', $this->attribute);
        $this->assertInstanceOf('DateTime', $this->model->name);
        $this->handler->setModelValue($this->model, '08-08-1990', $this->attribute);
        $this->assertInstanceOf('DateTime', $this->model->name);

        $this->handler->setModelValue($this->model, new \DateTime(), $this->attribute);
        $this->assertInstanceOf('DateTime', $this->model->name);

        $this->handler->setModelValue($this->model, '', $this->attribute);
        $this->assertNull($this->model->name);
    }


    /** @test */
    public function attribute_right_view()
    {
        $this->assertInstanceOf(AttributeView::class, $this->handler->view($this->model, $this->attribute));
    }
}
