<?php namespace Just\Shapeshifter\Attributes;

use Just\Shapeshifter\Exceptions\MissingArgumentException;

class ForcedFormatAttribute extends Attribute implements iAttributeInterface
{
    protected $format;

    public function __construct($name, $format = '', $flags = array())
    {
        if ( ! $format)
        {
            $className = get_class($this);

            throw new MissingArgumentException("Missing argument [format] in [$className]");
        }

        $this->format = $format;

        parent::__construct($name, $flags);
    }
}

?>
