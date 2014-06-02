<?php namespace Just\Shapeshifter\Core\Controllers;

use Controller;
use Illuminate\Database\Eloquent\Collection;
use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Exceptions\ClassNotExistException;
use Just\Shapeshifter\Exceptions\PropertyNotExistException;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Helpers\TimestampHelper;
use Just\Shapeshifter\Repository;
use Just\Shapeshifter\Services\AttributeService;
use Just\Shapeshifter\Services\BreadcrumbService;
use Just\Shapeshifter\Services\MenuService;
use Notification;
use Redirect;
use Request;
use Route;
use Sentry;
use URL;
use View;
use HTML;

abstract class AdminController extends Controller {

    public $data = array();
    public $attributes = array();
    public $mode;
    public $repo;

    protected $allowTimestamps = true;
    protected $disabledActions = array();
    protected $descriptor = "id";
    protected $orderby = array('sortorder', 'asc');
    protected $rules = array();
    protected $parent = null;

    /**
     * @return mixed
     */
    abstract protected function configureFields();

    /**
     *
     */
    public function __construct()
    {
        $this->checkRequirements();

        $this->repo = new Repository(new $this->model);
        $this->repo->setRules($this->rules);
        $this->repo->setOrderby($this->orderby);
    }

    /**
     *
     */
    protected function initAttributes()
    {
        $this->configureFields();

        if ($this->allowTimestamps)
        {
            $this->addTimestampFields();
        }

        $this->repo->setAttributes($this->attributes, $this->repo->getRules());

        $this->data['routes'] = $this->getCurrentRouteNames();
    }

    /**
     * @return mixed
     * @throws \Just\ShapeShifter\ShapeShifterException
     */
    public function index()
    {
        if ( ! $this->userHasAccess() ) {
            return $this->setupView('no_access');
        }

        $this->initAttributes();
        $this->generateTimestampFields();

        $this->mode = 'index';
        $this->model = $this->repo->getNew();

        $records = $this->repo->getListRecords($this->orderby, $this->getParentInfo());

        $this->data['ids'] = func_get_args();
        $this->data['title'] = $this->plural;
        $this->data['records'] = $records;

        return $this->setupView('index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        if ( ! $this->userHasAccess() ) {
            return $this->setupView('no_access');
        }

        $this->initAttributes();

        $this->mode = 'create';

        $this->model = $this->repo->getNew();

        $this->data['title'] = $this->singular . ' ' . strtolower(__('form.create'));
        $this->data['ids'] = func_get_args();
        $this->data['parent'] = $this->getParentInfo();

        return $this->setupView('form');
    }

    /**
     * @return mixed
     */
    public function edit()
    {
        if ( ! $this->userHasAccess() ) {
            return $this->setupView('no_access');
        }

        $this->initAttributes();

        $this->mode = 'edit';

        $this->data['ids'] = func_get_args();
        $this->model = $this->repo->findById(last($this->data['ids']));
        $this->data['title'] = $this->getDescriptor() == 'id' ? $this->singular . ' ' . strtolower(__('list.' . $this->mode)) : strip_tags($this->model->{$this->getDescriptor()});

        return $this->setupView('form');
    }

    /**
     * @return mixed
     */
    public function store()
    {
        if ( ! $this->userHasAccess() ) {
            return $this->setupView('no_access');
        }

        $this->initAttributes();

        $this->mode = 'store';
        $this->data['ids'] = func_get_args();

        try {
            $this->repo->save($this, $this->getParentInfo());
        } catch (ValidationException $e) {
            $errors = array_map('strtolower', $e->getErrors()->all());
            $errors = array_map('ucfirst', $errors);

            Notification::error(HTML::ul($errors, array('style' => 'margin:-1.375em 0;')));

            return Redirect::back()->withInput();
        }

        Notification::success(__('form.stored'));

        return Redirect::route($this->data['routes']['index'], $this->data['ids']);
    }

    /**
     * @return mixed
     */
    public function update()
    {
        if ( ! $this->userHasAccess() ) {
            return $this->setupView('no_access');
        }

        $this->initAttributes();

        $this->mode = 'update';

        $this->data['ids'] = func_get_args();
        $this->model = $this->repo->findById(last($this->data['ids']));

        try {
            $this->repo->save($this);
        } catch (ValidationException $e) {
            $errors = array_map('strtolower', $e->getErrors()->all());
            $errors = array_map('ucfirst', $errors);

            Notification::error(HTML::ul($errors, array('style' => 'margin:-1.375em 0;')));

            return Redirect::back()->withInput();
        }

        Notification::success(__('form.updated'));

        return Redirect::route($this->data['routes']['index'], $this->data['ids']);
    }

    /**
     * @return mixed
     */
    public function destroy()
    {
        if ( ! $this->userHasAccess() ) {
            return $this->setupView('no_access');
        }

        $this->mode = 'destroy';

        $id = last(func_get_args());

        $this->model = $this->repo->findById($id);
        $this->model = $this->beforeDestroy($this->model);

        if ( $this->repo->delete() ) {
            Notification::success('Het item is verwijderd.');
        }

        return Redirect::back();
    }

    /**
     * @param $object
     * @param array $options
     */
    protected function add($object, $options = array())
    {
        $object->setTab(isset($options['tab']) ? $options['tab'] : false);
        $object->setHelpText(isset($options['help']) ? $options['help'] : false);

        $this->attributes[$object->name] = $object;
    }

    /**
     * @param $template
     * @return mixed
     */
    protected function setupView($template)
    {
        $attributeService = new AttributeService();
        $breadcrumbService = new BreadcrumbService();
        $menuService = new MenuService();

        $user = Sentry::getUser();
        $user->setDisabledActions($this->disabledActions);

        $this->data['currentUser'] = $user;
        $this->data['breadcrumbs'] = $breadcrumbService->breadcrumbs();
        $this->data['menu'] = $menuService->generateMenu();
        $this->data['tabs'] = $attributeService->attributesToTabs($this->mode, $this->attributes, $this->model);
        $this->data['descriptor'] = $this->getDescriptor();
        $this->data['cancel'] = $this->generateCancelLink();
        $this->data['disabledActions'] = $this->disabledActions;
        $this->data['model'] = $this->model;

        $this->data['attributes'] = $this->attributes;
        $this->data['lastVisibleAttribute'] = $this->getLastVisibleAttribute();
        $this->data['singular'] = $this->singular;
        $this->data['mode'] = $this->mode;
        $this->data['controller'] = get_class($this);
        $this->data['parent'] = $this->parent;

        return View::make("shapeshifter::{$template}", $this->data);
    }

    /**
     * @return array
     */
    protected function getCurrentRouteNames()
    {
        $verbs = array('update', 'edit', 'index', 'destroy', 'create', 'store');
        $current = $this->getCurrentRouteName();

        foreach ($verbs as $verb) {
            $destinations[$verb] = $current . '.' . $verb;
        }

        return $destinations;
    }

    /**
     * @return bool
     */
    protected function userHasAccess()
    {
        return Sentry::getUser()->isSuperUser() || Sentry::getUser()->hasAnyAccess(array(Route::currentRouteName()));
    }

    /**
     * @return array
     */
    private function getParentInfo()
    {
        if ( $this->parent ) {
            //lame
            $segs = array_reverse(Request::segments());

            foreach ($segs as $seg) {
                if ( is_numeric($seg) ) return array($this->parent, (int)$seg);
            }
        }

        return array();
    }

    /**
     * @return string
     */
    private function generateCancelLink()
    {
        if ( ! isset($this->data['ids']) ) return array();

        $parameters = $this->data['ids'];

        if ( $this->repo->hasParent($this->getParentInfo()) ) {
            $edit = explode('.', $this->data['routes']['edit']);
            unset($edit[count($edit) - 2]);

            array_pop($parameters);

            return route(implode('.', $edit), $parameters);
        }

        return route($this->data['routes']['index'], $parameters);
    }

    /**
     * @return string
     */
    private function getCurrentRouteName()
    {
        $current = Route::currentRouteName();
        $current = explode('.', $current);

        array_pop($current);

        return implode('.', $current);
    }

    /**
     *
     */
    private function generateTimestampFields()
    {
        $ts = new TimestampHelper();

        $ts->createTimestampFields($this->repo->getModel()->getTable());
    }

    /**
     * @throws \Just\Shapeshifter\Exceptions\ClassNotExistException
     * @throws \Just\Shapeshifter\Exceptions\PropertyNotExistException
     */
    private function checkRequirements()
    {
        if ( ! isset($this->singular) || ! isset($this->plural) || ! isset($this->model) ) {
            throw new PropertyNotExistException("Property [singular] or [plural] or [model] does not exist");
        }
        else if ( ! class_exists($this->model) ) {
            throw new ClassNotExistException("Class [{$this->model}] doest not exist");
        }
    }

    private function getLastVisibleAttribute()
    {
        $last = null;
        foreach ($this->attributes as $attribute)
        {
            if ( ! $attribute->hasFlag('hide_list')) {
                $last = $attribute;
            }
        }

        return $last;
    }

    private function addTimestampFields()
    {
        $this->add(new Attribute\ReadonlyAttribute('updated_at', array('hide_add', 'hide_list')));
        $this->add(new Attribute\ReadonlyAttribute('created_at', array('hide_add', 'hide_list')));
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->plural;
    }

    /**
     * @return string
     */
    public function getDescriptor()
    {
        return $this->descriptor;
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderby;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function beforeAdd($model)
    {
        return $model;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function beforeUpdate($model)
    {
        return $model;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function afterAdd($model)
    {
        return $model;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function afterUpdate($model)
    {
        return $model;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function beforeDestroy($model)
    {
        return $model;
    }
}

?>
