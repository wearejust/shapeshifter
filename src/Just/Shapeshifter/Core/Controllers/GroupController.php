<?php namespace Just\Shapeshifter\Core\Controllers;

use Input;
use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Relations as Relation;
use Just\Shapeshifter\Services\AttributeService;

class GroupController extends AdminController
{
    protected $singular = "Groep";
    protected $plural = "Groepen";

    protected $model = 'Just\Shapeshifter\Core\Models\Group';
    protected $descriptor = "name";
    protected $orderby = array('name','asc');

    protected $rules = array(
        'name' => 'required',
    );

    protected function configureFields()
    {
        $this->add( new Attribute\TextAttribute('name'));
        $this->add( new Relation\ManyToManyFacebookRelation($this, 'admin.users', 'users'));

        foreach ($this->getAllPermissionRoutes() as $p)
        {
            $this->add( new Attribute\CheckboxAttribute("perms[{$p}]", array('no_save', 'hide_list')));
        }
    }

    //override the edit method because we have custom permission fields
    public function edit()
    {
        if ( ! $this->userHasAccess()) {
            return $this->setupView('no_access');
        }

        $this->initAttributes();

        $this->mode = 'edit';

        $this->data['ids'] = func_get_args();
        $this->model = $this->repo->findById(last($this->data['ids']));

        // Custom //
        $count = 1;
        foreach ($perms = $this->model->permissions as $k=>$p) {
            $perms[$k] = $count;
            $count++;
        }

        foreach (array_flip($perms) as $k=>$p) $perms[$k] = 'perms[' . $p . ']';
        Input::merge(array_flip($perms));
        // End Custom //

        $this->data['title'] = $this->getDescriptor() == 'id' ? $this->singular . ' ' . strtolower(__('list.' . $this->mode)) : strip_tags($this->model->{$this->getDescriptor()});

        return $this->setupView('form');
    }

    public function beforeAdd($model)
    {
        return $this->beforeUpdate($model);
    }

    public function beforeUpdate($model)
    {
        foreach ($perms = Input::get('perms', array()) as $k=>$p) $perms[$k] = intval($p);

        $all = array_fill_keys(array_keys(array_flip($this->getAllPermissionRoutes())), 0);
        $mixed = array_merge($all, $perms);

        $model->permissions = $mixed;


        return $model;
    }

    protected function getAllPermissionRoutes()
    {
        $all = new AttributeService();
        return $all->getAllPermissions();
    }
}

?>
