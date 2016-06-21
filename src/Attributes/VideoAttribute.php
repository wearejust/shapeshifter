<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Helpers\VideoHelper;

/**
 * VimeoAttribute
 *
 * @uses     Attribute
 *
 * @category Category
 *
 * @author   JUST BV
 *
 * @link     http://wearejust.com/
 */
class VideoAttribute extends Attribute implements iAttributeInterface
{
    public function getDisplayValue(Model $model)
    {
        return VideoHelper::preview($model->{$this->name});
    }
}
