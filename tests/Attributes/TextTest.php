<?php

namespace Just\Shapeshifter\Test\Attributes;

use Just\Shapeshifter\Attributes\Text;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class TextTest extends \PHPUnit_Framework_TestCase
{
    protected $attribute;
    protected $attribute2;
    protected $model;

    public function setUp()
    {
        $this->attribute = new Text('test', 'text');
        $this->attribute2 = new Text('test', 'email');
        $this->model = new TestModel();

    }

    /** @test */
    public function attribute_has_properties()
    {
        $this->assertTrue('test' === $this->attribute->getName());
        $this->assertTrue('text' === $this->attribute->getType());
        $this->assertTrue('email' === $this->attribute2->getType());
    }

}
