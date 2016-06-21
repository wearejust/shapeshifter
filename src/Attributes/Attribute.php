<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use View;

abstract class Attribute
{
    /**
     * The name of the attribute
     *
     * @var string
     */
    public $name;

    /**
     * The value of the attribute of the Model
     *
     * @var string
     */
    public $value;

    /**
     * Flags for the attribute
     *
     * @var array
     */
    public $flags;

    /**
     * The system uses tabs in forms. You can specify 
     * the tab within the Controller
     *
     * @var mixed
     */
    public $tab;

    /**
     * Standard tab of the attribute if isn't specified
     *
     * @var string
     */
    public $standardtab = '_default';

    /**
     * You can set various helptexts to help the user 
     * fill inl the right data
     *
     * @var mixed
     */
    public $helptext;

    /**
     * If the boolean is set to false, the attribute isn't required.
     * If true, an * will apear by the label
     *
     * @var bool
     */
    public $required = false;

    /**
     * @param string $name  Description.
     * @param array  $flags Description.
     */
    public function __construct($name = '', $flags = [])
    {
        $this->name  = $name;
        $this->flags = $flags;
    }

    /**
     * Base display function to display the view of the attribute.
     * Each attribute has it's own view, with the name (ReadonlyAttribute.blade.php)
     * as an name
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function compile(Model $model = null)
    {
        if ($this->hasFlag('readonly')) {
            return $this->readonly();
        }

        return $this->view($model);
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function readonly()
    {
        return $this->view('ReadonlyAttribute');
    }

    /**
     * @param Model $model
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function view(Model $model)
    {
        $data = get_object_vars($this);
        $data = array_add($data, 'label', $this->getLabel($this->name));
        $data = array_add($data, 'model', $model);

        $attribute = (new ReflectionClass($this))->getShortName();

        return View::make('shapeshifter::attributes.' . $attribute, $data)->render();
    }

    /**
     * This function is fired when in edit mode (form). This function returns
     * an string, which is placed into the form field
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string .
     */
    public function getEditValue(Model $model)
    {
        return $model->{$this->name};
    }

    /**
     * Sets the value of the model
     *
     * @access public
     *
     * @param $value
     * @param $oldValue
     *
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
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed Value.
     */
    public function getDisplayValue(Model $model)
    {
        return $model->{$this->name};
    }

    /**
     * This function is fired when an record is saved. It means each attribute can
     * have it's own function to specifiy what is saved in the Database.
     *
     * @param Model $model
     */
    public function getSaveValue(Model $model)
    {
        $model->{$this->name} = $this->value;
    }

    /**
     * Sets the tab
     *
     * @param mixed $tab Description.
     *
     * @access public
     *
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
     *
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
     *
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
     *
     * @return mixed Value.
     */
    public function hasFlag($flag = false)
    {
        return $this->flags && is_array($this->flags) && in_array($flag, $this->flags);
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function getLabel($name)
    {
        $label = translateAttribute($name);
        $label = str_replace('_', ' ', $label);

        if ($this->required) {
            $label .= ' *';
        }

        return $label;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function afterSave(Model $model)
    {
        //
    }
}
