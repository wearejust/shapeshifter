<?php namespace Just\Shapeshifter\Attributes;


/**
* CheckboxAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class CheckboxAttribute extends Attribute implements iAttributeInterface
{
    /**
     * getEditValue
     * 
     * @access public
     * @return mixed Value.
     */
    public function getEditValue()
    {
        return $this->value;
    }

    public function setAttributeValue($value, $oldValue = 0)
    {
        $this->value = $value ?: 0;
    }

    /**
     * getDisplayValue
     * 
     * @access public
     * @return mixed Value.
     */
    public function getDisplayValue()
    {
        return $this->value ? 'Ja' : 'Nee';
    }

    /**
     * getSaveValue
     * 
     * @access public
     * @return mixed Value.
     */
    public function getSaveValue()
    {
        return $this->value ?: 0;
    }
}

?>
