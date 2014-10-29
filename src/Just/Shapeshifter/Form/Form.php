<?php namespace Just\Shapeshifter\Form;

use Closure;
use Illuminate\Support\Collection;
use Just\Shapeshifter\Attributes\Attribute;
use Just\Shapeshifter\Core\Models\Language;
use Just\Shapeshifter\Form\Elements\Section;
use Just\Shapeshifter\Form\Elements\Tab;


class Form
{
	/**
	 * @var Collection
	 */
	public $attributes;

	/**
	 * @var Collection
	 */
	protected $tabs;

	/**
	 * @var Collection
	 */
	protected $sections;

	/**
	 * @var
	 */
	protected $mode;


	/**
	 * Holds the translatable attributes.
	 * @var Collection
	 */
	public $translation_attributes;

	/**
	 *
	 */
	public function __construct ()
	{
		$this->attributes             = new Collection();
		$this->tabs                   = new Collection();
		$this->sections               = new Collection();
		$this->lang                   = new Language();
		$this->translation_attributes = array();
	}

	/**
	 * @param Attribute $attribute
	 */
	public function add (Attribute $attribute)
	{
		if ($this->mode === 'create' && $attribute->hasFlag('hide_add'))
		{
			return;
		}

		if ($attribute->hasFlag('translate'))
		{
			array_push($this->translation_attributes, $attribute);
		} else
		{
			$this->attributes->push($attribute);
		}
	}

	/**
	 * @param          $name
	 * @param callable $callback
	 */
	public function tab ($name, Closure $callback)
	{
		$tab = new Tab($name);

		call_user_func($callback, $tab);

		$this->tabs->push($tab);
	}

	/**
	 * @param          $name
	 * @param callable $callback
	 */
	public function section ($name, Closure $callback)
	{
		$section = new Section($name);

		call_user_func($callback, $section);

		$this->sections->push($section);
	}

	/**
	 *
	 */
	public function render ()
	{
		if ($this->tabs->count())
		{
			$this->appendLooseAttributes();
		}
	}

	/**
	 * @return Collection
	 */
	public function getAttributes ()
	{
		return $this->attributes;
	}

	/**
	 * @return Collection
	 */
	public function getTabs ()
	{
		return $this->tabs;
	}

	/**
	 * @return Collection
	 */
	public function getSections ()
	{
		return $this->sections;
	}

	/**
	 *
	 */
	protected function appendLooseAttributes ()
	{
		foreach ($this->tabs as $tab)
		{
			if ($tab->getName() === 'Algemeen')
			{
				$tab->setAttributes($tab->merge($this->attributes));

				$this->attributes = new Collection();
			}
		}

		if ($this->attributes->count())
		{
			$tab = new Tab('Algemeen');
			$tab->setAttributes($this->attributes);

			$this->tabs->prepend($tab);

			$this->attributes = new Collection();
		}


	}

	/**
	 * @return Collection
	 */
	public function getAllAttributes ()
	{
		$all = new Collection();

		foreach ($this->attributes as $attr)
		{
			$all->put($attr->name, $attr);
		}

		foreach ($this->sections->all() as $section)
		{
			foreach ($section->getAttributes() as $attr)
			{
				$all->put($attr->name, $attr);
			}
		}

		foreach ($this->tabs->all() as $tab)
		{
			foreach ($tab->getAttributes() as $attr)
			{
				$all->put($attr->name, $attr);
			}
			foreach ($tab->getSections() as $section)
			{
				foreach ($section as $attr)
				{
					$all->put($attr->name, $attr);
				}
			}
		}

		return $all;
	}

	/**
	 * @param $mode
	 */
	public function setMode ($mode)
	{
		$this->mode = $mode;
	}

} 
