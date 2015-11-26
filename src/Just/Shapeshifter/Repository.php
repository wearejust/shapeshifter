<?php namespace Just\Shapeshifter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Input;
use Just\Shapeshifter\Attributes\ReadonlyAttribute;
use Just\Shapeshifter\Core\Models\Language;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Services\AttributeService;
use Schema;

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

		$this->languagesInit();
	}

	/**
	 * @param       $orderBy
	 * @param array $filters
	 * @param array $parent
	 *
	 * @return mixed
	 */
	public function all ($orderBy, $filters = array(), $parent = array(), $paginate = false)
	{
		$records = $this->getRecords($orderBy, $filters, $parent, $paginate);

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
		if ($this->modelHasTranslations())
		{
			$this->model = $this->model->with('translations')->find($id);
		} else
		{
			$this->model = $this->model->find($id);
		}

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

		$relations = new Collection;

		$this->validate();

		$this->mutateAttributes();

		$this->setSortorderForAdd($parent);

		$this->checkForParent($parent);

		$this->checkEventActions($ref);

		foreach ($this->attributes as $k=>$a)
        {
            if (get_class($a) == 'Just\Shapeshifter\Attributes\CustomAttribute') {
                unset($this->model[$k]);
            }
        }

        $adding = !$this->model->id;

		if ($this->model->save())
		{
			$translatableSaveItems = $this->prepareTranslations($relations, $parent);
			$this->saveTranslations($translatableSaveItems);

			$this->model = $adding ? $ref->afterAdd($this->model) : $ref->afterUpdate($this->model);

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
		$search = Input::get('search');

		foreach ($attributes as $key => $attr)
		{
			if ($key == 'translations')
			{
				continue;
			}

			if ($mode == 'index') {
				$attr->sortable = 'href="?' . ($search ? "{$search}&" : '') . "sort={$attr->name}&sortdir=";
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
	private function getRecords ($orderBy, $filters, $parent, $paginate)
	{
		$table = $this->model->getTable();
		$query = $this->model;

		if ($this->hasParent($parent))
		{
			$query = $query->where($parent[0], $parent[1]);
		}

		foreach ($filters as $filter)
		{
			if (strpos($filter, '.') !== false) {
				$rel = explode('.', $filter);
				$query = $query->with($rel[0])->whereHas($rel[0], function($q) use ($filter) {
					$q->whereRaw($filter);
				});
			} else {
				$query = $query->whereRaw($filter);
			}
		}

		$sort = $paginate ? Input::get('sort') : false;
		$sortTable = $sort ? Schema::hasColumn($table, $sort) : false;
		if ($sort && $sortTable) {
			$orderBy = array($sort, Input::get('sortdir'));
		}

		$orderByTranslate = false;
		if ($this->modelHasTranslations() && substr($orderBy[0], 0, 10) == 'translate.') {
			$orderByTranslate = explode('.', $orderBy[0]);
			$orderByTranslate = array_pop($orderByTranslate);
			$orderBy[0] = 'id';
		}
		$records = $query->orderBy($orderBy[0], $orderBy[1]);

		if ($search = Input::get('search')) {
			if ($relation = Input::get('search_relation')) {
				$relation = explode('.', $relation);
				$records = $records->with($relation[0])->whereHas($relation[0], function($q) use ($relation, $search) {
					$q->where($relation[1], 'LIKE', "%{$search}%");
				});

			} else {
				$records = $records->where(function($q) use ($table, $search) {
					foreach ($this->attributes as $attribute) {
						if (Schema::hasColumn($table, $attribute->name) && !in_array('hide_list', $attribute->flags)) {
							$q->orWhere($attribute->name, 'LIKE', "%{$search}%");
						}
					}
				});
			}
		}

        $count = Input::get('count', is_int($paginate) ? $paginate : 25);

		if ($paginate && !($sort && !$sortTable)) {
			return $records->paginate(($count == 'all') ? PHP_INT_MAX : $count);

		} else {
			$records = $records->get();

			if ($orderByTranslate) {
				$lang = \Config::get('app.locale');
				$lang = \Language::where('short_code', $lang)->first();
				if ($lang) {
					$records->sortBy(function($rec) use ($orderByTranslate, $lang) {
						foreach ($rec->translations as $translation) {
							if ($translation->language_id == $lang->id && $translation->attribute == $orderByTranslate) {
								return $translation->value;
							}
						}
					});
				}
			}

			return $records;
		}
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
	 * @param $relations
	 *
	 * @return array
	 */
	private function getSaveabletranslations ($relations)
	{
		$translatableSaveItems = array();
		foreach ($relations as $item)
		{
			$class_name   = get_class($this->model->translations()->getModel());
			$class        = new $class_name;
			$existingItem = $class->where('parent_id', '=', $this->model->id)
			                      ->where('language_id', '=', $item['language_id'])
			                      ->where('attribute', '=', $item['attribute'])
			                      ->first();
			if ($existingItem)
			{
				foreach ($item as $key => $value)
				{
					$existingItem->{$key} = $value;
				}

				$translatableSaveItems[] = $existingItem;
			} else
			{
				$translatableSaveItems[] = $class->create($item);
			}

		}
		return $translatableSaveItems;
	}

	/**
	 * @param $relations
	 */
	private function convertTranslationInputToModels ($relations, $parent)
	{
		$inputs = Input::all();
		$translations = $inputs['translations'];

		//print_r(Input::all() );
		//print_r(Input::get('translations') );





		$i=1;
		foreach ($translations as $key => $lang_attributes)
		{
			foreach($lang_attributes as $type => $value)
			{
				$lang = $this->languages->where('short_code', '=', $key)->get(array('id'))->first();
				if (isset($parent[1]) && !empty($parent))
				{
					$parentId = $parent[1];
				} else
				{
					$parentId = $this->model->id;
				}

				//echo $type;

				$relations->put($i, array(
					'parent_id'   => $parentId,
					'language_id' => $lang->id,
					'attribute'   => $type,
					'value'       => $value
				));
				$i++;
			}

		}
		//die();
	}

	/**
	 * @param $translatableSaveItems
	 */
	private function saveTranslations ($translatableSaveItems)
	{
		//dd($translatableSaveItems);
		if ($this->modelHasTranslations())
		{
			$this->model->translations()->saveMany($translatableSaveItems);
		}
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
	 * @param $relations
	 *
	 * @return array
	 */
	private function prepareTranslations ($relations, $parent)
	{

		//dd(\Input::all());

		if (\Input::has('translations') && $this->langIsEnabled())
		{
			$this->convertTranslationInputToModels($relations, $parent);
		}
		$translatableSaveItems = array();
		if ($this->modelHasTranslations())
		{
			$translatableSaveItems = $this->getSaveabletranslations($relations);
			return $translatableSaveItems;
		}
		return $translatableSaveItems;
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
	public function langIsEnabled () {
		return \Config::get('shapeshifter::config.translation') && \Schema::hasTable('languages');
	}

}
