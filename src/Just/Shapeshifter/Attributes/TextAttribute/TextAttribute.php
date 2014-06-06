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
class TextAttribute extends Attribute implements iAttributeInterface{

    /**
     * Type of the input field
     * 
     * aka:email, url, tel, number
     *
     * @var string
     */
    public $type;

    public function __construct($name = '', $type = 'text', $flags = array() )
    {
        $this->name = $name;
        $this->type = $type;
        $this->flags = $flags;
    }
}

?>
