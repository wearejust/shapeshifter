<?php namespace Just\Shapeshifter\Attributes;

/**
* iAttributeInterface
*
* @uses     
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
interface iAttributeInterface
{
    public function setAttributeValue($value, $oldValue);
    public function getDisplayValue();
    public function getEditValue();
    public function getSaveValue();
    public function compile();
    public function __toString();
}

?>
