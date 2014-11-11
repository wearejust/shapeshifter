<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Relations as Relation;

class UserController extends AdminController
{
    protected $singular = "Gebruiker";
    protected $plural = "Gebruikers";

    protected $model = 'Just\Shapeshifter\Core\Models\User';
    protected $descriptor = "name";
    protected $orderby = array('email','asc');
    protected $disabledActions = array(
        'delete',
        'drag'
    );

    protected $rules = array(
        'name' => 'required',
    );

    protected function configureFields(Form $modifier)
    {
        $modifier->add( new Attribute\CheckboxAttribute('activated'));

        $modifier->add( new Attribute\TextAttribute('email', 'email'));
        $modifier->add( new Attribute\TextAttribute('first_name', 'text',array('hide_list')));
        $modifier->add( new Attribute\TextAttribute('last_name', 'text',array('hide_list')));
        $modifier->add( new Attribute\PasswordAttribute('password', array('hide_list')));
        $modifier->add( new Attribute\PasswordAttribute('password_confirmation', array('hide_list', 'no_save')));

        $modifier->add( new Relation\ManyToManyFacebookRelation($this, 'groups', 'groups'));

        $modifier->add( new Attribute\ReadonlyAttribute('last_login', array('hide_add')));
    }

    protected function afterInit(Form $modifier)
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
