<?php

namespace Just\Shapeshifter\Attributes;

use Just\Shapeshifter\Attributes\Handlers\TextHandler;

class Text extends Attribute
{
    /**
     * @var string
     */
    protected $type;

    /**
     * TextAttribute constructor.
     *
     * @param string $name
     * @param string $type
     * @param array  $flags
     */
    public function __construct($name = '', $type = 'text', array $flags = [])
    {
        $this->type = $type;

        parent::__construct($name, $flags);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return string
     */
    public function getHandler()
    {
        return Handlers\TextHandler::class;
    }
}
