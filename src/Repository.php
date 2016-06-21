<?php

namespace Just\Shapeshifter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\Attributes\ReadonlyAttribute;
use Just\Shapeshifter\Core\Controllers\AdminController;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Services\AttributeService;

class Repository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var
     */
    protected $attributes;

    /**
     * @var
     */
    protected $rules;

    /**
     * @var
     */
    protected $orderby;

    /**
     * @var Application
     */
    private $app;

    /**
     * @param Model       $model
     * @param Application $app
     */
    public function __construct(Model $model, Application $app)
    {
        $this->model     = $model;
        $this->app       = $app;
    }

    /**
     * @param       $orderBy
     * @param array $filters
     * @param array $parent
     *
     * @return mixed
     */
    public function all($orderBy, array $filters = [], array $parent = [])
    {
        $records = $this->getRecords($orderBy, $filters, $parent);

        return $this->app->make(AttributeService::class, [$this->attributes])
            ->mutateRecords($records);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param string $a
     * @param string $b
     *
     * @return mixed
     */
    public function listed($a, $b)
    {
        return $this->model->lists($a, $b);
    }

    /**
     * @param AdminController$ref
     * @param array $parent
     *
     * @return mixed
     *
     * @throws Exceptions\ValidationException
     */
    public function save(AdminController $ref, array $parent = [])
    {
        $this->validate();
        $this->mutateAttributes();
        $this->setSortorderForAdd($parent);
        $this->checkForParent($parent);
        $this->checkEventActions($ref);

        if (count($parent) && $ref->getMode() === 'store') {
            unset($this->model->id);
        }

        if ($this->model->save()) {
            $this->model = ($ref->getMode() === 'store') ? $ref->afterAdd($this->model) : $ref->afterUpdate($this->model);

            $this->afterSaveAttributes();

            if ($this->model->save()) {
                return $this->model->id;
            }
        }

        throw new ValidationException('Error');
    }

    /**
     * @param $parent
     *
     * @return bool
     */
    public function hasParent($parent)
    {
        return count($parent) === 2;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete(Model $model)
    {
        return $model->delete();
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
     * @param mixed $rules
     */
    public function setRules($rules)
    {
        foreach ($rules as &$rule) {
            $rule = (is_string($rule)) ? explode('|', $rule) : $rule;
        }

        $this->rules = $rules;
    }

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param $attributes
     * @param $rules
     */
    public function setAttributes($attributes, $rules)
    {
        foreach ($attributes as $key => $attribute) {
            if (AttributeService::ignoreAttributes($attribute)) {
                unset($attributes[$key]);
            }

            $attribute->setRequired(
                array_key_exists($attribute->name, $rules) && in_array('required', $rules[$attribute->name])
            );
        }

        $this->attributes = $attributes;
    }

    /**
     * @param $mode
     * @param Collection|Attribute[] $attributes
     * @param $model
     *
     * @return mixed
     */
    public function setAttributeValues($mode, Collection $attributes, Model $model)
    {
        foreach ($attributes as $key => $attr) {
            if ($mode === 'create' && $attr->hasFlag('hide_add')) {
                $attributes->forget($key);
            }

            if ($mode === 'edit') {
                if ($attr->hasFlag('hide_edit')) {
                    $attributes->forget($key);
                }

                $model->{$attr->name} = $attr->getEditValue($model);
            }
        }

        return $attributes;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $orderBy
     */
    public function setOrderby($orderBy)
    {
        $this->orderby = $orderBy;
    }

    /**
     * @return mixed
     */
    public function getOrderby()
    {
        return $this->orderby;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * @param $orderBy
     * @param $filters
     * @param $parent
     *
     * @return mixed
     */
    private function getRecords($orderBy, $filters, $parent)
    {
        $query = $this->model;

        if ($this->hasParent($parent)) {
            $query = $query->where($parent[0], $parent[1]);
        }

        foreach ($filters as $filter) {
            $query = $query->whereRaw($filter);
        }

        $records = $query->orderBy($orderBy[0], $orderBy[1])->get();

        return $records;
    }

    /**
     * @throws Exceptions\ValidationException
     */
    private function validate()
    {
        $messages = $this->app['translator']->get('shapeshifter::validation');

        $validator = $this->app['validator']->make($this->app['request']->all(), $this->rules, $messages);
        $validator->setAttributeNames($messages['attributes']);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }

    /**
     *
     */
    private function mutateAttributes()
    {
        foreach ($this->attributes as $attr) {
            if ($attr instanceof ReadonlyAttribute || in_array('no_save', $attr->flags)) {
                continue;
            }

            $attr->setAttributeValue($this->app['request']->get($attr->name), $this->model->{$attr->name});
            $attr->getSaveValue($this->model);
        }
    }

    /**
     *
     */
    private function afterSaveAttributes()
    {
        foreach ($this->attributes as $attr) {
            $attr->afterSave($this->model);
        }
    }
    /**
     * @param $parent
     */
    private function checkForParent($parent)
    {
        if ($this->hasParent($parent)) {
            $this->model->{$parent[0]} = $parent[1];
        }
    }

    /**
     * @param $parent
     */
    private function setSortorderForAdd($parent)
    {
        if (!$this->model->id && \Schema::hasColumn($this->model->getTable(), 'sortorder')) {
            $query = $this->app['db']->table($this->model->getTable());

            if ($this->hasParent($parent)) {
                $query = $query->where($parent[0], $parent[1]);
            }

            $max = $query->max($this->orderby[0]) + 1;

            $this->model->{$this->orderby[0]} = $max;
        }
    }

    /**
     * @param $ref
     */
    protected function checkEventActions(AdminController $ref)
    {
        if (null === $ref->getParent()) {
            $this->model = !$this->model->id ? $ref->beforeAdd($this->model) : $ref->beforeUpdate($this->model);
        } else {
            $ref->getMode() === 'store'
                ? $ref->beforeAdd($this->model)
                : $ref->beforeUpdate($this->model);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}
