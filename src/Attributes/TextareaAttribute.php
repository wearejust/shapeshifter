<?php

namespace Just\Shapeshifter\Attributes;

class TextareaAttribute extends Attribute implements iAttributeInterface
{
    /**
     * The numer of cols of the attribute
     *
     * @var mixed
     */
    protected $cols;

    /**
     * The numer of rows of the attribute
     *
     * @var mixed
     */
    protected $rows;

    /**
     * @param string $name       Name of the attribute
     * @param array  $dimensions The dimensions of the attribute ($rows, $cols).
     * @param array  $flags      Flags
     */
    public function __construct($name = '', $dimensions = [], $flags = [])
    {
        $this->name  = $name;
        $this->flags = $flags;

        $this->rows = isset($dimensions[0]) ? $dimensions[0] : 7;
        $this->cols = isset($dimensions[1]) ? $dimensions[1] : 15;
    }
}
