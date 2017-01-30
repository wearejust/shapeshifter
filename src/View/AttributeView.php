<?php

namespace Just\Shapeshifter\View;

class AttributeView
{
    /**
     * @var
     */
    private $template;

    /**
     * @var array
     */
    private $data;

    /**
     * @param       $template
     * @param array $data
     */
    public function __construct($template, array $data)
    {
        $this->template = $template;
        $this->data = $data;
    }
}
