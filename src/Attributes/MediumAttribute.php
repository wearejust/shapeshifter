<?php

namespace Just\Shapeshifter\Attributes;

class MediumAttribute extends Attribute implements iAttributeInterface
{
    /**
     * The numer of cols of the attribute
     *
     * @var mixed
     *
     * @access protected
     */
    protected $cols;

    /**
     * The numer of rows of the attribute
     *
     * @var mixed
     *
     * @access protected
     */
    protected $rows;

    /**
     * __construct
     * 
     * @param string $name  Name of the attribute
     * @param array  $flags Flags
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function __construct($name = '', $flags = ['hide_list'])
    {
        $this->name  = $name;
        $this->flags = $flags;

        $this->rows = 7;
        $this->cols = 15;
    }
}
