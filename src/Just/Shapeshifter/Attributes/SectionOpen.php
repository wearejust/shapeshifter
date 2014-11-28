<?php namespace Just\Shapeshifter\Attributes;

/**
* SectionOpen
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class SectionOpen extends Attribute implements iAttributeInterface {

	public function __construct($name = '', $flags = array())
	{
		$this->name = $name;
		$this->flags = $flags;
	}

}