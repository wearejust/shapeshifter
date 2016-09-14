<?php

namespace Just\Shapeshifter\Relations;

use Illuminate\Database\Eloquent\Model;
use View;

class ManyToManyCheckboxRelation extends ManyToManyFacebookRelation
{
    /**
     * display
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     *
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     */
    public function compile(Model $model = null)
    {
        $this->checkDestinationModel($model);

        $descriptor = $this->destination->getDescriptor();
        $table      = $this->destination->getRepository()->getModel()->getTable();
        $results    = $model->{$this->function}()->get([$table . '.id', "{$descriptor} as name"])->lists('id');
        $all        = $this->destination->getRepository()->getModel()->get([$table . '.id', "{$descriptor} as name"])->lists('name', 'id');

        return View::make('shapeshifter::relations.ManyToManyCheckboxRelation',  [
            'results' => $results,
            'all'     => $all,
            'name'    => $this->name,
            'label'   => translateAttribute($this->name)
        ])->render();
    }

    /**
     * @param $val
     * @param null $oldValue
     *
     * @return mixed|void
     */
    public function setAttributeValue($val, $oldValue = null)
    {
        if (is_array($val)) {
            $this->value = array_keys($val);
        } else {
            parent::setAttributeValue($val, $oldValue);
        }
    }
}
