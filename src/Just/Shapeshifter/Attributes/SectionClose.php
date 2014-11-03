<?php namespace Just\Shapeshifter\Attributes;

/**
* SectionClose
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class SectionClose extends Attribute implements iAttributeInterface {

	public function __construct($flags = array())
	{
		$this->flags = $flags;
	}

}