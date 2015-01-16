<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Relations as Relation;

class GroupController extends AdminController
{
    protected $singular = "Groep";
    protected $plural = "Groepen";

    protected $model = 'Just\Shapeshifter\Core\Models\Group';
    protected $descriptor = "name";
    protected $orderby = array('name','asc');
    protected $disabledActions = array('drag', 'sort');

    protected $rules = array(
        'name' => 'required',
    );

    protected function configureFields(Form $modifier)
    {
        $modifier->add( new Attribute\TextAttribute('name'));
        $modifier->add( new Relation\ManyToManyFacebookRelation($this, 'users', 'users'));

        foreach ($this->getAllPermissionRoutes() as $p)
        {
            $modifier->add( new Attribute\CheckboxAttribute("perms[{$p}]", array('no_save', 'hide_list')));
        }
    }

    public function beforeInit(Form $modifier)
    {
        $count = 1;
        foreach ($perms = $this->model->permissions as $k=>$p) {
            $perms[$k] = $count;
            $count++;
        }

        foreach (array_flip($perms) as $k=>$p) $perms[$k] = 'perms[' . $p . ']';

        $this->app['request']->merge(array_flip($perms));
    }

    public function beforeAdd($model)
    {
        return $this->beforeUpdate($model);
    }

    public function beforeUpdate($model)
    {
        foreach ($perms = \Input::get('perms', array()) as $k=>$p) $perms[$k] = intval($p);

        $all = array_fill_keys(array_keys(array_flip($this->getAllPermissionRoutes())), 0);
        $mixed = array_merge($all, $perms);

        $model->permissions = $mixed;

        return $model;
    }

    protected function getAllPermissionRoutes()
    {
        return $this->app->make('Just\Shapeshifter\Services\AttributeService')->getAllPermissions();
    }
}

?>
