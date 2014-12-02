<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Relations as Relation;

class UserController extends AdminController
{
	protected $singular = "Gebruiker";
	protected $plural   = "Gebruikers";

	protected $model           = 'Just\Shapeshifter\Core\Models\User';
	protected $descriptor      = "name";
	protected $orderby         = array('email', 'asc');
	protected $disabledActions = array(
		'drag'
	);

	protected $rules = array(
		'first_name' => 'required',
		'last_name'  => 'required',
		'email'      => 'required|email'
	);

	protected function configureFields (Form $modifier)
	{
		$modifier->add(new Attribute\CheckboxAttribute('activated'));

		$modifier->add(new Attribute\TextAttribute('email', 'email'));
		$modifier->add(new Attribute\TextAttribute('first_name', 'text', array('hide_list')));
		$modifier->add(new Attribute\TextAttribute('last_name', 'text', array('hide_list')));
		$modifier->add(new Attribute\PasswordAttribute('password', array('hide_list')));
		$modifier->add(new Attribute\PasswordAttribute('password_confirmation', array('hide_list', 'no_save')));

		$modifier->add(new Relation\ManyToManyFacebookRelation($this, 'groups', 'groups'));

		$modifier->add(new Attribute\ReadonlyAttribute('last_login', array('hide_add')));
	}

	protected function beforeInit (Form $modifier)
	{
		if ($this->mode == 'store')
		{
			$this->rules['password']   = 'required|confirmed';
			$this->rules['first_name'] = 'required';
			$this->rules['last_name']  = 'required';
		}
		else if ($this->mode == 'update')
		{
			$this->rules['first_name'] = 'required';
			$this->rules['last_name']  = 'required';
		}
	}
}

?>
