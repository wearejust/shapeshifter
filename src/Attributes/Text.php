<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Contracts\Support\Arrayable;
use Just\Shapeshifter\Attributes\Handlers\TextHandler;

class Text extends Attribute implements Arrayable
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

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'type' => $this->getType()
        ]);
    }
}
