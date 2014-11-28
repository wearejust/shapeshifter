<?php namespace Just\Shapeshifter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Input;
use Just\Shapeshifter\Attributes\ReadonlyAttribute;
use Just\Shapeshifter\Core\Models\Language;
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

	public $languages;

	/**
	 * @param $model
	 */
	public function __construct (Model $model, Application $app)
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
	public function all ($orderBy, $filters = array(), $parent = array())
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
	 *
	 * @return mixed
	 */
	public function findById ($id)
	{
		$this->model = $this->model->find($id);
		return $this->model;
	}

	/**
	 * @param $field1
	 * @param $field2
	 *
	 * @return mixed
	 */
	public function listed ($field1, $field2)
	{
		return $this->model->lists($field1, $field2);
	}

	/**
	 * @param       $ref
	 * @param array $parent
	 *
	 * @return mixed
	 * @throws Exceptions\ValidationException
	 */
	public function save ($ref, $parent = array())
	{

		$this->validate();

		$this->mutateAttributes();

		$this->setSortorderForAdd($parent);

		$this->checkForParent($parent);

		$this->checkEventActions($ref);

		if(isset($parent) && $ref->mode == 'store') {
			unset($this->model->id);
		}


		foreach ($this->attributes as $k=>$a)
		{
			if (get_class($a) == 'Just\Shapeshifter\Attributes\CustomAttribute') {
				unset($this->model->attribut[$k]);
			}
		}

		if ($this->model->save())
		{
			$this->model = !$this->model->id ? $ref->afterAdd($this->model) : $ref->afterUpdate($this->model);

			if ($this->model->save())
			{
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
	public function hasParent ($parent)
	{
		return count($parent) === 2;
	}

	/**
	 * @return mixed
	 */
	public function delete ()
	{
		return $this->model->delete();
	}

	/**
	 * @param array $attributes
	 *
	 * @return mixed
	 */
	public function getNew ($attributes = array())
	{
		return $this->model->newInstance($attributes);
	}

	/**
	 * @param mixed $rules
	 */
	public function setRules ($rules)
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
	public function getRules ()
	{
		return $this->rules;
	}


	/**
	 * @param $attributes
	 * @param $rules
	 */
	public function setAttributes ($attributes, $rules)
	{
		foreach ($attributes as $key => $attribute)
		{
			if (AttributeService::ignoreAttributes($attribute)) unset($attributes[$key]);

			if (isset($rules[$attribute->name]) && preg_grep('/required/', $rules[$attribute->name]) !== false)
			{
				$attribute->setRequired(true);
			}
		}

		$this->attributes = $attributes;
	}

	/**
	 * @param $mode
	 * @param $attributes
	 * @param $model
	 *
	 * @return mixed
	 */
	public function setAttributeValues ($mode, $attributes, $model)
	{
		foreach ($attributes as $key => $attr)
		{
			if ($key == 'translations')
			{
				continue;
			}

			if ($mode == 'create' && $attr->hasFlag('hide_add')) $attributes->forget($key);

			if ($mode === 'edit')
			{
				if ($attr->hasFlag('hide_edit')) $attributes->forget($key);

				$attr->setAttributeValue($model->{$attr->name});

				if (get_class($attr) == 'Just\Shapeshifter\Attributes\CustomAttribute') {
					$val = $attr->getEditValue($model);
				} else {
					$val = $attr->getEditValue($attr->value);
				}
				$model->{$attr->name} = $val;
			}
		}

		return $attributes;
	}

	/**
	 * @return mixed
	 */
	public function getAttributes ()
	{
		return $this->attributes;
	}

	/**
	 * @return mixed
	 */
	public function getModel ()
	{
		return $this->model;
	}

	/**
	 * @param mixed $orderBy
	 */
	public function setOrderby ($orderBy)
	{
		$this->orderby = $orderBy;
	}

	/**
	 * @return mixed
	 */
	public function getOrderby ()
	{
		return $this->orderby;
	}

	/**
	 * @return string
	 */
	public function getTable ()
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
	private function getRecords ($orderBy, $filters, $parent)
	{
		$query = $this->model;

		if ($this->hasParent($parent))
		{
			$query = $query->where($parent[0], $parent[1]);
		}

		foreach ($filters as $filter)
		{
			$query = $query->whereRaw($filter);
		}

		$records = $query->orderBy($orderBy[0], $orderBy[1])->get();

		return $records;
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	private function validate ()
	{
		$messages = $this->app['translator']->get('shapeshifter::validation');

		$validator = $this->app['validator']->make($this->app['request']->all(), $this->rules, $messages);
		$validator->setAttributeNames($messages['attributes']);

		if ($validator->fails())
		{
			throw new ValidationException($validator->errors());
		}
	}

	/**
	 *
	 */
	private function mutateAttributes ()
	{
		foreach ($this->attributes as $attr)
		{
			if (in_array('no_save', $attr->flags) || $attr instanceof ReadonlyAttribute) continue;
			$attr->setAttributeValue($this->app['request']->get($attr->name), $this->model->{$attr->name});

			if (get_class($attr) == 'Just\Shapeshifter\Attributes\CustomAttribute') {
				$value = $attr->getSaveValue($this->model, $attr->value);
			} else {
				$value = $attr->getSaveValue();
			}

			if (!is_null($value))
			{
				$this->model->{$attr->name} = $value;
			}
		}
	}

	/**
	 * @return bool
	 */
	public function modelHasTranslations ()
	{
		$relation = $this->model->getTable() . '_translations';
		$check    = \Schema::hasTable($relation);
		return $check;
	}

	/**
	 * @param $parent
	 */
	private function checkForParent ($parent)
	{
		if ($this->hasParent($parent))
		{
			$this->model->{$parent[0]} = $parent[1];
		}
	}

	/**
	 * @param $parent
	 */
	private function setSortorderForAdd ($parent)
	{
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
	}

	/**
	 * @param $ref
	 */
	protected function checkEventActions ($ref)
	{
		$parentIsActive = $ref->getParent();

		if (is_null($parentIsActive))
		{
			$this->model = !$this->model->id ? $ref->beforeAdd($this->model) : $ref->beforeUpdate($this->model);
		}
		else
		{
			($ref->mode == 'store')
				? $ref->beforeAdd($this->model)
				: $ref->beforeUpdate($this->model);
		}
	}

	protected function languagesInit ()
	{
		$this->languages = ($this->langIsEnabled()) ? new Language : '';
	}

	/**
	 *
	 */
	private function langIsEnabled () {
		return \Schema::hasTable('languages');
	}

}