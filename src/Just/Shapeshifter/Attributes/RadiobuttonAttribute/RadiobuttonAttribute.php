<?php namespace Just\Shapeshifter\Attributes;

/**
* RadiobuttonAttribute
*
* @uses     DropdownAttribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class RadiobuttonAttribute extends DropdownAttribute implements iAttributeInterface
{
    /**
     * __construct
     * 
     * @param string $name   The database column of the attribute.
     * @param array  $values An array of values
     * @param array  $flags  Flags
     *
     * @access public
     * @return mixed Value.
     */
	public function __construct($name = '', $values = array(), $flags = array())
	{
		parent::__construct($name, $values, $flags);

		$this->customlabel = true;
	}
}

?>