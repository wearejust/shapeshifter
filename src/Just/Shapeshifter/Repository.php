<?php namespace Just\Shapeshifter;

use DB;
use Input;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Services\AttributeService;
use Lang;
use Notification;
use Request;
use Schema;
use Session;
use Validator;

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
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $orderBy
     * @param $parentField
     * @return mixed
     */
    public function getListRecords($orderBy, $parentField = array(), $filter = array())
    {
        $service = new AttributeService();
        $query = $this->model;

        if (count($parentField) == 2)
        {
            $query = $query->where($parentField[0], $parentField[1]);
        }

        foreach ($filter as $fil)
        {
            $query = $query->whereRaw($fil);
        }

        $records = $query->orderBy($orderBy[0], $orderBy[1])->get();
        $records = $service->mutateList($records, $this->attributes);

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
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
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
        $messages = Lang::get('shapeshifter::validation');

        $validator = Validator::make(Input::all(), $this->rules, $messages);
        $validator->setAttributeNames($messages['attributes']);

        if ($validator->fails())
        {
            throw new ValidationException($validator->errors());
        }

        foreach ($this->attributes as $attr)
        {
            if (in_array('no_save', $attr->flags)) continue;

            $attr->setAttributeValue(Input::get($attr->name), $this->model->{$attr->name});
            $value = $attr->getSaveValue();

            if (!is_null($value)) {
                $this->model->{$attr->name} = $value;
            }
        }

        //Set sortorder for add
        if (!$this->model->id && Schema::hasColumn($this->model->getTable(), 'sortorder'))
        {
            $query = DB::table($this->model->getTable());

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
        return count($parent) == 2;
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
        foreach ($attributes as $key => $attribute) {
            if  (AttributeService::ignoreAttributes($attribute)) unset($attributes[$key]);

            if (isset($rules[$attribute->name]) && array_search('required', $rules[$attribute->name]) !== false) {
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
}
