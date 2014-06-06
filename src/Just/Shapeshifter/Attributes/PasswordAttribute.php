<?php namespace Just\Shapeshifter\Attributes;


/**
* PasswordAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class PasswordAttribute extends Attribute implements iAttributeInterface
{
    /**
     * getEditValue
     * 
     * @access public
     * @return mixed Value.
     */
    public function getEditValue()
    {
        return '';
    }

    /**
     * getDisplayValue
     * 
     * @access public
     * @return mixed Value.
     */
    public function getDisplayValue()
    {
        return null;
    }

    public function getSaveValue()
    {
        if (!$this->value) return null;

        return $this->value;
    }


}

?>