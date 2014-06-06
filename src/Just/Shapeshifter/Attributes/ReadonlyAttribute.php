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
class ReadonlyAttribute extends Attribute implements iAttributeInterface{

    public function getSaveValue()
    {
        return null;
    }
}

?>
