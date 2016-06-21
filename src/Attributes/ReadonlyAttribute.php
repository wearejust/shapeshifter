<?php

namespace Just\Shapeshifter\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * TextAttribute
 *
 * @uses     Attribute
 *
 * @category Category
 *
 * @author   JUST BV
 *
 * @link     http://wearejust.com/
 */
class ReadonlyAttribute extends Attribute implements iAttributeInterface
{
    /**
     * This function is fired when an record is saved. It means each attribute can
     * have it's own function to specifiy what is saved in the Database.
     *
     * @param Model $model
     */
    public function getSaveValue(Model $model)
    {
        return $model;
    }
}
