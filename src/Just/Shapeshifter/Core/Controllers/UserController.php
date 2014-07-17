<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Relations as Relation;

class UserController extends AdminController
{
    protected $singular = "Gebruiker";
    protected $plural = "Gebruikers";

    protected $model = 'Just\Shapeshifter\Core\Models\User';
    protected $descriptor = "last_name";
    protected $orderby = array('email','asc');
    protected $disabledActions = array(
        'delete',
        'drag'
    );

    protected $rules = array(
        'name' => 'required',
    );

    protected function configureFields()
    {
        $this->add( new Attribute\CheckboxAttribute('activated'));

        $this->add( new Attribute\TextAttribute('email', 'email'));
        $this->add( new Attribute\TextAttribute('name', 'text',array('hide_list')));
        $this->add( new Attribute\PasswordAttribute('password', array('hide_list')));
        $this->add( new Attribute\PasswordAttribute('password_confirmation', array('hide_list', 'no_save')));

        $this->add( new Relation\ManyToManyFacebookRelation($this, 'groups', 'groups'));

        $this->add( new Attribute\ReadonlyAttribute('last_login', array('hide_add')));
    }

    protected function afterInit()
    {
        if ($this->mode == 'store')
        {
            $this->rules['password'] = 'required|confirmed';
            $this->rules['email'] = 'required|email|unique:cms_users,email';
        }
        else if ($this->mode == 'update')
        {
            $this->rules['email'] = 'required|email|unique:cms_users,email,' . $this->getCurrentId();
        }
    }
}

?>
