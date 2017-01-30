<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Exceptions\NoOptionsProvided;
use Just\Shapeshifter\View\AttributeView;

class Dropdown extends Attribute
{
    /**
     * All the values of the current attribute
     *
     * @var array
     *
     * @access protected
     */
    protected $options;

    /**
     * @param string $name   Description.
     * @param array  $options Description.
     * @param array  $flags  Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function __construct($name, array $options, $flags = [])
    {
        if (count($options) === 0) {
            throw new NoOptionsProvided(sprintf('No options provided for [%s]', $name));
        }

        if (false === $this->isAssoc($options)) {
            $options = array_combine($options, $options);
        }

        $this->options = $options;

        parent::__construct($name, $flags);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return Handlers\DropdownHandler::class;
    }

    /**
     * http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
     *
     * @param array $options
     *
     * @return bool
     */
    private function isAssoc(array $options)
    {
        return array_keys($options) !== range(0, count($options) - 1);
    }
}
