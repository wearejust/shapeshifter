<?php namespace Just\Shapeshifter\Attributes;

/**
* TextAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class HiddenAttribute extends Attribute implements iAttributeInterface{

    /**
     * Type of the input field
     * 
     * aka:email, url, tel, number
     *
     * @var string
     */
    public $type;

    public function __construct($name = '', $value, $flags = array() )
    {
        $this->name = $name;
	    $this->value = $value;
        $this->type = 'hidden';
        $this->flags = $flags;
    }
}

?>
