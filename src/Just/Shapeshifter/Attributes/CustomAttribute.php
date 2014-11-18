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
    public $attributeName;
    public $getValue;
    public $setValue;

    public function __construct($name = '', $attributeName = null, $getValue = null, $setValue = null, $flags = array())
    {
        $this->name = $name;
        $this->attributeName = $attributeName ?: 'ReadonlyAttribute';
        $this->getValue = $getValue;
        $this->setValue = $setValue;
        $this->flags = $flags;

        $this->attribute = \App::make('Just\Shapeshifter\Attributes\\'.$this->attributeName);
        $this->attribute->name = $name;
        $this->attribute->flags = $flags;
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
