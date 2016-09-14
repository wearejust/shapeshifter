<?php

namespace Just\Shapeshifter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Form\Form;

class Repository
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @var Model
     */
    private $model;

    /**
     * @param Form  $form
     * @param Model $model
     */
    public function __construct(Form $form, Model $model)
    {
        $this->form = $form;
        $this->model = $model;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $rules
     * @param array $parent
     *
     * @return mixed
     */
    public function store($rules, array $parent = [])
    {
        $model = $this->getNew();

        return $this->save($model, $parent, $rules);
    }

    /**
     * @param Model $model
     * @param       $rules
     * @param array $parent
     *
     * @return mixed
     * @throws ValidationException
     */
    public function save(Model $model, $rules, array $parent = [])
    {
        $this->validate($rules);
        $this->mutateAttributes($model);
        //$this->setSortorderForAdd($parent);
        $this->checkForParent($parent);

        $model->save();

        return $model;
    }

    /**
     * @param Model $model
     *
     * @return bool
     * @throws \Exception
     */
    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * @return Builder
     */
    public function getNewQuery()
    {
        return $this->model->newQuery();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function getNew($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * @throws Exceptions\ValidationException
     */
    public function validate(array $rules)
    {
        $messages = app('translator')->get('shapeshifter::validation');
        $validator = app('validator')->make(request()->all(), $rules, $messages);
        $validator->setAttributeNames($messages['attributes']);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }

    /**
     * @param Model $model
     */
    private function mutateAttributes(Model $model)
    {
        foreach ($this->form->getAllAttributes() as $attr) {
            $attr->setAttributeValue(request()->get($attr->name), $model->{$attr->name});
            $attr->getSaveValue($model);
        }
    }


    /**
     * @param array $parent
     */
    private function checkForParent(array $parent)
    {
        if (count($parent) === 2) {
            $this->model->{$parent[0]} = $parent[1];
        }
    }
}
