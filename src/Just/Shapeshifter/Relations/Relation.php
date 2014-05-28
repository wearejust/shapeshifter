<?php namespace Just\Shapeshifter\Relations;

use Just\Shapeshifter\Attributes\Attribute as Attribute;

/**
* Relation
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
abstract class Relation extends Attribute
{
    /**
     * $activemodel
     *
     * @var mixed
     *
     * @access protected
     */
	protected $activemodel;
}