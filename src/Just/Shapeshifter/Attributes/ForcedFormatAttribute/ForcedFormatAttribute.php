<?php namespace Just\Shapeshifter\Attributes;

class ForcedFormatAttribute extends Attribute implements iAttributeInterface
{
    public $format;

    public function __construct($name, $format = '', $flags = array())
    {
        $this->format = $format;

        parent::__construct($name, $flags);
    }
}

?>
