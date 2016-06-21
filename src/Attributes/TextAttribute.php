<?php

namespace Just\Shapeshifter\Attributes;

class TextAttribute extends Attribute implements iAttributeInterface
{
    /**
     * Type of the input field
     * 
     * aka:email, url, tel, number
     *
     * @var string
     */
    public $type;

    /**
     * TextAttribute constructor.
     *
     * @param string $name
     * @param string $type
     * @param array  $flags
     */
    public function __construct($name = '', $type = 'text', $flags = [])
    {
        $this->flags = $flags;

        parent::__construct($name, $flags);
    }
}
