<?php

namespace Just\Shapeshifter\Attributes;

use Just\Shapeshifter\Exceptions\MissingArgumentException;

class ForcedFormatAttribute extends Attribute implements iAttributeInterface
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @param string $name
     * @param string $format
     * @param array  $flags
     */
    public function __construct($name, $format = '', $flags = [])
    {
        if (! $format) {
            $className = get_class($this);

            throw new MissingArgumentException("Missing argument [format] in [$className]");
        }

        $this->format = $format;

        parent::__construct($name, $flags);
    }
}
