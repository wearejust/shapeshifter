<?php namespace Just\Shapeshifter\Attributes;

use App;
use View;
use Just\Shapeshifter\Exceptions\AttributeErrorException;
/**
* Attribute
*
* @uses     
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
abstract class Attribute
{
    /**
     * The name of the attribute
     *
     * @var string
     *
     * @access public
     */
    public $name;

    /**
     * The value of the attribute of the Model
     *
     * @var string
     *
    * @access public
     *
     */
    public $value;

    /**
     * Flags for the attribute
     *
     * @var array
     *
     * @access public
     */
    public $flags;

    /**
     * The system uses tabs in forms. You can specify 
     * the tab within the Controller
     *
     * @var mixed
     *
     * @access public
     */
    public $tab;

    /**
     * Standard tab of the attribute if isn't specified
     *
     * @var string
     *
     * @access public
     */
    public $standardtab = '_default';

    /**
     * You can set various helptexts to help the user 
     * fill inl the right data
     *
     * @var mixed
     *
     * @access public
     */
    public $helptext;

    /**
     * If the boolean is set to false, the attribute isn't required.
     * If true, an * will apear by the label
     *
     * @var boolean
     *
     * @access public
     */
    public $required = false;

    /**
     * Generated HTML for the attribute
     *
     * @var string
     */
    protected $html = '';

    /**
     * __construct
     *
     * @param string $name  Description.
     * @param array  $flags Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function __construct($name = '', $flags = array() )
    {
        $this->name = $name;
        $this->flags = $flags;
    }

    /**
     * Base display function to display the view of the attribute.
     * Each attribute has it's own view, with the name (ReadonlyAttribute.blade.php)
     * as an name
     *
     * @access public
     * @return mixed Value.
     */
    public function compile()
    {
        $attribute = App::make('ReflectionClass', array($this))->getShortName();

        if ($this->hasFlag('readonly'))
        {
            $this->html = $this->readonly();
        }
        else
        {
            $this->html = $this->view($attribute);
        }
    }

    public function __toString()
    {
	    try {
		    $this->compile();
		    return $this->html;
	    } catch (\Exception $exception) {
		    throw new AttributeErrorException($exception);
	    }
    }

    protected function readonly()
    {
        $attribute = 'ReadonlyAttribute';

        return $this->view($attribute);
    }


    protected function view($attribute)
    {
        $array = array();
        foreach (get_object_vars($this) as $k=>$item) {
            ${$k} = $item; $array[] = $k;
        }

        $label = $this->getLabel($name);
        $array[] = 'label';

        return View::make('shapeshifter::attributes.'.$attribute, compact($array))->render();
    }

    /**
     * This function is fired when in edit mode (form). This function returns
     * an string, which is placed into the form field
     *
     * @access public
     * @return string.
     */
    public function getEditValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the model
     *
     * @access public
     * @param $value
     * @param $oldValue
     * @return mixed Value.
     */
    public function setAttributeValue($value, $oldValue = null)
    {
        $this->value = $value ?: '';
    }

    /**
     * This function is fired when in an list view. Each attribute can have it's own function
     * and the return value is the thing you can see in the list
     *
     *
     * @access public
     * @return mixed Value.
     */
    public function getDisplayValue()
    {
        return $this->value;
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
        return $this->value;
    }

    /**
     * Sets the tab
     *
     * @param mixed $tab Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function setTab($tab)
    {
        $this->tab = $tab ?: $this->standardtab;
    }

    /**
     * setRequired
     *
     * @param mixed $required Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * setHelpText
     *
     * @param mixed $text Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function setHelpText($text)
    {
        $this->helptext = $text ?: false;
    }

    /**
     * hasFlag
     *
     * @param mixed $flag Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function hasFlag($flag = false)
    {
        return $this->flags && is_array($this->flags) && in_array($flag, $this->flags);
    }

    /**
     * addFlag
     *
     * @param mixed $flag Description.
     *
     * @access public
     * @return mixed Value.
     */
    public function addFlag($flag)
    {
        return $this->flags[] = $flag;
    }

    /**
     * @param $name
     * @return string
     */
    protected function getLabel($name)
    {
        $label = translateAttribute($name);

        $label = str_replace("_", " ", $label);

        if ( $this->required ) {
            $label .= ' *';
        }

        return $label;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function hide()
    {
        $this->flags[] = 'hide';
    }

    public function show()
    {
        if (($key = array_search('hide', $this->flags)) !== false)
        {
            unset($this->flags[$key]);
        }
    }

}

?>
