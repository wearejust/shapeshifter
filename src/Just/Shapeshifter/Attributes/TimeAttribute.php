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
class TimeAttribute extends Attribute implements iAttributeInterface
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
	 * @param string $name  Description.
	 * @param array  $lists
	 * @param array  $flags Description.
	 *
	 * @internal param array $values Description.
	 * @access   public
	 * @return mixed Value.
	 */
    public function __construct($name = '', $flags = array())
    {
		$this->name = $name;
		$this->flags = $flags;
        $this->values = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,30,21,22,23);
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