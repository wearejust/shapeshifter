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
        $this->values = array("00"=>"00","01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06","07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12","13"=>"13","14"=>"14","15"=>"15","16"=>"16","17"=>"17","18"=>"18","19"=>"19", "20"=>"20","21"=>"21","22"=>"22","23"=>"23");
        $this->values2 = array("00"=>"00","05"=>"05","10"=>"10","15"=>"15","20"=>"20","25"=>"25","30"=>"30","35"=>"35","40"=>"40","45"=>"45","50"=>"50","55"=>"55");

        $this->value = "";
        $this->value2 = "";




	}

    /**
     * Returns the value of the attribute
     * 
     * @access public
     * @return mixed Value.
     */
    public function getEditValue()
    {

        //dd($this->value);
        $parts = explode(":", $this->value);




        $this->value = $parts[0];
        if(isset($parts[1])) {
            $this->value2 = $parts[1];
        }

        //dd($delen);
        //
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
        //dd($this->value);
        return $this->value;
        $parts = explode(":", $this->value);
        $this->value = $parts[0];
        $this->value2 = $parts[1];



        if (array_key_exists($this->value, $this->values)) 
        {
            return $this->values[$this->value];
        }

        return 'Geen';
    }

    public function getSaveValue()
    {
        //dd(\Input::all());

        return \Input::get($this->name ).":".\Input::get($this->name."_min" );

        //return $this->value . $this->value2";
    }


}