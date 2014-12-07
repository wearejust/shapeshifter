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
class CustomAttribute extends Attribute
{
    public $attribute;
    public $getValue;
    public $setValue;

    public function __construct($attribute = null, $params = null, $getValue = null, $setValue = null)
    {
        $this->getValue = $getValue;
        $this->setValue = $setValue;

        if (is_string($params)) {
            $params = array($params);
        }

        if (!isset($params[1])) {
            if ($attribute == 'TextAttribute') {
                $params[1] = 'text';
                $flags = 2;
            } else if ($attribute == 'DropdownAttribute') {
                $params[1] = array();
                $flags = 2;
            }
        }

        $flags = 1;
        if (in_array($attribute, array('TextAttribute', 'DropdownAttribute'))) {
            $flags = 2;
        }

        if (!isset($params[$flags])) {
            $params[$flags] = array();
        }

        $this->name = $params[0];
        $this->flags = $params[$flags];

        $this->attribute = \App::make('Just\Shapeshifter\Attributes\\' . ($attribute ?: 'ReadonlyAttribute'), $params);
    }

    public function getDisplayValue($rec = null)
    {
        if ($this->getValue !== null) {
            $this->attribute->setAttributeValue(call_user_func($this->getValue, $rec));
        }
        return $this->attribute->getDisplayValue();
    }

    public function getEditValue($rec = null)
    {
        if ($this->getValue !== null) {
            $this->attribute->setAttributeValue(call_user_func($this->getValue, $rec));
        }
        return $this->attribute->getEditValue();
    }

    public function getSaveValue($rec = null, $val = null)
    {
        if ($this->setValue !== null) {
            return call_user_func($this->setValue, $rec, $val);
        }
        return $this->attribute->getSaveValue();
    }

    public function __toString()
    {
        return $this->attribute->__toString();
    }
}

?>
