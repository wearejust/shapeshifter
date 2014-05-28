<?php namespace Just\Shapeshifter\Attributes;

use Just\Shapeshifter\Helpers\VideoHelper;

/**
 * VimeoAttribute
 *
 * @uses     Attribute
 *
 * @category Category
 * @package  Package
 * @author   JUST BV
 * @link     http://wearejust.com/
 */
class VideoAttribute extends Attribute implements iAttributeInterface
{
    public function getDisplayValue()
    {
        return VideoHelper::preview($this->value);
    }
}

?>
