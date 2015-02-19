<?php namespace Just\Shapeshifter\Attributes;

/**
* TextareaAttribute
*
* @uses     Attribute
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class TextareaAttribute extends Attribute implements iAttributeInterface
{
    /**
     * The numer of cols of the attribute
     *
     * @var mixed
     *
     * @access protected
     */
	protected $cols;


    /**
     * The numer of rows of the attribute
     *
     * @var mixed
     *
     * @access protected
     */
	protected $rows;

    /**
     * __construct
     * 
     * @param string $name       Name of the attribute
     * @param array  $dimensions The dimensions of the attribute ($rows, $cols).
     * @param array  $flags      Flags
     *
     * @access public
     * @return mixed Value.
     */
	public function __construct($name = '', $dimensions = array(), $flags = array())
	{
		$this->name = $name;
		$this->flags = $flags;

		$this->rows = isset($dimensions['rows']) ? $dimensions['rows'] : 7;
		$this->cols = isset($dimensions['cols']) ? $dimensions['cols'] : 15;
	}
	
	/**
	 * This function is fired when an record is saved. It means each attribute can
	 * have it's own function to specifiy what is saved in the Database.
	 *
	 * @access public
	 * @return mixed Value.
	 */
	public function getSaveValue()
	{
		return nl2br($this->value);
	}
}

?>