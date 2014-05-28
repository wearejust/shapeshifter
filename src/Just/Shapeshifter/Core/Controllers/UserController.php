<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Relations as Relation;

class UserController extends AdminController
{
    protected $singular = "Gebruiker";
    protected $plural = "Gebruikers";

    protected $model = 'Just\Shapeshifter\Core\Models\User';
    protected $descriptor = "last_name";
    protected $orderby = array('id','asc');
    protected $disabledActions = array(
        'drag'
    );

    protected $rules = array(
        'email' => 'required|email|unique:cms_users,email',
        'first_name' => 'required',
        'last_name' => 'required',
        'password' => 'confirmed',
    );

    protected function configureFields()
    {
        $this->add( new Attribute\CheckboxAttribute('activated'));

        $this->add( new Attribute\TextAttribute('email'));
        $this->add( new Attribute\TextAttribute('first_name', array('hide_list')));
        $this->add( new Attribute\TextAttribute('last_name', array('hide_list')));
        $this->add( new Attribute\PasswordAttribute('password', array('hide_list')));
        $this->add( new Attribute\PasswordAttribute('password_confirmation', array('hide_list', 'no_save')));

        $this->add( new Relation\ManyToManyFacebookRelation($this, 'admin.groups', 'groups'));

        $this->add( new Attribute\ReadonlyAttribute('last_login', array('hide_add')));
    }

    public function update()
    {
        $id = last(func_get_args());
        $this->rules['email'] = 'required|email|unique:cms_users,email,' . $id;

        $this->repo->setRules($this->rules);

        return call_user_func_array("parent::update", func_get_args());
    }
}

?>
