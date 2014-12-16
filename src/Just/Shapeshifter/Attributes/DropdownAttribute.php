<?php namespace Just\Shapeshifter\Attributes;

/**
* DropdownAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class DropdownAttribute extends Attribute implements iAttributeInterface
{
   /**
    * All the values of the current attribute
    *
    * @var mixed
    *
    * @access protected
    */   
    protected $values;

    /**
     * __construct
     * 
     * @param string $name   Description.
     * @param array  $values Description.
     * @param array  $flags  Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function __construct($name = '', $values = array(), $labels = array(), $flags = array())
    {
        $this->name = $name;
        $this->flags = $flags;

        if ( ! $labels) $labels = $values;

        $this->values = array_combine($labels, $values);
    }

    /**
     * Returns the value of the attribute
     * 
     * @access public
     * @return mixed Value.
     */
    public function getEditValue()
    {
        if ( ! $this->value ) return null;

        return $this->value;
    }

    /**
     * Returns the label that belongs to the value
     * 
     * @access public
     * @return string Value.
     */
    public function getDisplayValue()
    {
        if (array_key_exists($this->value, $this->values)) 
        {
            return $this->values[$this->value];
        }

        return 'Geen';
    }
}

?>
