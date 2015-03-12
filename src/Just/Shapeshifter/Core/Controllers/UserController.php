<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Relations as Relation;
use Sentry;

class UserController extends AdminController {
	protected $singular = "Gebruiker";
	protected $plural = "Gebruikers";

	protected $model = 'Just\Shapeshifter\Core\Models\User';
	protected $descriptor = "name";
	protected $orderby = array('email', 'asc');
	protected $disabledActions = array(
		'drag'
	);

	protected $rules = array(
		'name'  => 'required',
		'email' => 'required'
	);

	protected function configureFields(Form $modifier)
	{
		$modifier->add(new Attribute\CheckboxAttribute('activated'));

		$modifier->add(new Attribute\TextAttribute('email', 'email'));
		$modifier->add(new Attribute\TextAttribute('name', 'text', array('hide_list')));
		$modifier->add(new Attribute\PasswordAttribute('password', array('hide_list')));
		$modifier->add(new Attribute\PasswordAttribute('password_confirmation', array('hide_list', 'no_save')));

		$modifier->add(new Relation\ManyToManyCheckboxRelation($this, 'groups', 'groups'));

		$modifier->add(new Attribute\ReadonlyAttribute('last_login', array('hide_add')));
	}

	protected function beforeInit(Form $modifier)
	{
		if (!Sentry::getUser()->isSuperuser()) {
			$ids = array();
			$users = Sentry::findAllUsersWithAccess(array('superuser'));
			foreach ($users as $user) {
				$ids[] = $user->id;
			}

			$this->filter[] = 'id NOT IN (' . implode(',', $ids) . ')';
		}
	}

	public function beforeAdd($model)
	{
		if ($this->mode == 'store') {
			$this->rules['password'] = 'required|confirmed';
			$this->rules['email'] = 'required|email|unique:cms_users,email';
			$this->rules['name'] = 'required';
		} else if ($this->mode == 'update') {
			$this->rules['email'] = 'required|email';
			$this->rules['name'] = 'required';
		}
		return $model;
	}

	public function beforeUpdate($model)
	{
		if ($this->mode == 'store') {
			$this->rules['password'] = 'required|confirmed';
			$this->rules['email'] = 'required|email|unique:cms_users,email';
			$this->rules['name'] = 'required';
		} else if ($this->mode == 'update') {
			$this->rules['email'] = 'required|email';
			$this->rules['name'] = 'required';
		}
		return $model;
	}

}