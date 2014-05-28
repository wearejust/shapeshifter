<?php namespace Just\Shapeshifter\Attributes;

/**
* DateAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class DateAttribute extends Attribute implements iAttributeInterface
{
    /**
     * Returns the date in the dutch format. If no date is passed, nothing is returned.
     *
     * @access public
     * @return mixed Value.
     */
    public function getDisplayValue()
    {
        if ( ! $this->value) return null;

		return date('d-m-Y', strtotime($this->value));
   }

    /**
     * @return mixed
     */
    public function getEditValue()
    {
        return $this->getDisplayValue();
    }

    /**
     * Converts an dutch date string to an DateTime string (for the Database)
     *
     * @access public
     * @return mixed Value.
     */
    public function getSaveValue()
    {
        if ( ! $this->value) return null;

		return date('Y-m-d', strtotime($this->value));
    }
}

?>
