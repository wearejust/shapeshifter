<?php

namespace Just\Shapeshifter\Test\Attributes;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Test\Stubs\TestAttribute;
use Just\Shapeshifter\Test\Stubs\TestHandler;
use Just\Shapeshifter\Test\Stubs\TestModel;
use Just\Shapeshifter\View\AttributeView;

class AttributeTest extends \PHPUnit_Framework_TestCase
{
    protected $abstract;
    protected $model;

    public function setUp()
    {
        $this->abstract = new TestAttribute('name', ['flag1', 'flag2']);
        $this->model = new TestModel();

    }

    /** @test */
    public function flag_methods_are_ok()
    {
        $this->assertArrayHasKey('flag1', array_flip($this->abstract->getFlags()));
        $this->assertArrayHasKey('flag2', array_flip($this->abstract->getFlags()));
        $this->assertTrue($this->abstract->hasFlag('flag1'));
        $this->assertFalse($this->abstract->hasFlag('flag10'));
    }
//
//    /** @test */
//    public function compile_method_return_right_thing()
//    {
////        $abstract = new TestAttribute('name', ['flag1', 'readonly']);
//        $handler = new TestHandler();
//
//        $this->assertInstanceOf(AttributeView::class, $handler->compile($this->model));
//    }


}
