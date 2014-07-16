<?php namespace Just\Shapeshifter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Services\AttributeService;
use Notification;

class Repository
{
    /**
     * @var
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
     * @param $model
     */
    public function __construct(Model $model, Application $app)
    {
        $this->model = $model;
        $this->app = $app;
    }

    /**
     * @param $orderBy
     * @param array $filters
     * @param array $parent
     * @return mixed
     */
    public function all($orderBy, $filters = array(), $parent = array())
    {
        $records = $this->getRecords($orderBy, $filters, $parent);

        $service = $this->app->make(
            'Just\Shapeshifter\Services\AttributeService',
            array($this->attributes)
        );
        $records = $service->mutateRecords($records);

        return $records;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $this->model = $this->model->findOrFail($id);

        return $this->model;
    }

    /**
     * @param $field1
     * @param $field2
     * @return mixed
     */
    public function listed($field1, $field2)
    {
        return $this->model->lists($field1, $field2);
    }

    /**
     * @param $ref
     * @param array $parent
     * @return mixed
     * @throws Exceptions\ValidationException
     */
    public function save($ref, $parent = array())
    {
        // perform validation
        $messages = $this->app['translator']->get('shapeshifter::validation');

        $validator = $this->app['validator']->make($this->app['request']->all(), $this->rules, $messages);
        $validator->setAttributeNames($messages['attributes']);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }

        foreach ($this->attributes as $attr)
        {
            if (in_array('no_save', $attr->flags)) continue;

            $attr->setAttributeValue($this->app['request']->get($attr->name), $this->model->{$attr->name});
            $value = $attr->getSaveValue();

            if (!is_null($value)) {
                $this->model->{$attr->name} = $value;
            }
        }

        //Set sortorder for add
        if (!$this->model->id && \Schema::hasColumn($this->model->getTable(), 'sortorder'))
        {
            $query = $this->app['db']->table($this->model->getTable());

            if ($this->hasParent($parent))
            {
                $query = $query->where($parent[0], $parent[1]);
            }

            $max = $query->max($this->orderby[0]) + 1;

            $this->model->{$this->orderby[0]} = $max;
        }

        if ($this->hasParent($parent))
        {
            $this->model->{$parent[0]} = $parent[1];
        }

        $this->model = !$this->model->id ? $ref->beforeAdd($this->model) : $ref->beforeUpdate($this->model) ;

        if ( $this->model->save() )
        {
            $this->model = !$this->model->id ? $ref->afterAdd($this->model) : $ref->afterUpdate($this->model) ;

            if ($this->model->save()) {
                return $this->model->id;
            }
        }

        throw new ValidationException('Error');
    }

    /**
     * @param $parent
     * @return bool
     */
    public function hasParent($parent)
    {
        return count($parent) === 2;
    }

    /**
     * @return mixed
     */
    public function delete()
    {
        return $this->model->delete();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function getNew($attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules)
    {
        foreach ($rules as &$rule)
        {
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
        foreach ($attributes as $key => $attribute)
        {
            if  (AttributeService::ignoreAttributes($attribute)) unset($attributes[$key]);

            if (isset($rules[$attribute->name]) && preg_grep('/required/', $rules[$attribute->name]) !== false) {
                $attributes[$attribute->name]->setRequired(true);
            }
        }

        $this->attributes = $attributes;
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
     * @param $orderBy
     * @param $filters
     * @param $parent
     * @return mixed
     */
    private function getRecords($orderBy, $filters, $parent)
    {
        $query = $this->model;

        if ( $this->hasParent($parent) ) {
            $query = $query->where($parent[0], $parent[1]);
        }

        foreach ($filters as $filter) {
            $query = $query->whereRaw($filter);
        }

        $records = $query->orderBy($orderBy[0], $orderBy[1])->get();

        return $records;
    }

    public function getTable()
    {
        return $this->model->getTable();
    }
}
