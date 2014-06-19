<?php namespace Just\Shapeshifter\Core\Controllers;

use Controller;
use HTML;
use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Exceptions\ClassNotExistException;
use Just\Shapeshifter\Exceptions\InvalidArgumentException;
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

abstract class AdminController extends Controller {

    /**
     * @var \Just\Shapeshifter\Repository
     */
    public $repo;

    /**
     * The mode of the current action (create, edit)
     *
     * @var
     */
    public  $mode;

    /**
     * This data array holds all the data that will be send to the view
     *
     * @var array
     */
    protected $data = array();

    /**
     * All the attributes
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Allow timestamp fields (created_at, updated_at) in the node
     *
     * @var bool
     */
    protected $allowTimestamps = true;

    /**
     * Disable some actions in the node (drag, sort, create, delete)
     *
     * @var array
     */
    protected $disabledActions = array();

    /**
     * Array of record ids which cannot be deleted in the node
     *
     * @var array
     */
    protected $disableDeleting = array();

    /**
     * Array of record ids which cannot be edited in the node
     *
     * @var array
     */
    protected $disableEditing = array();

    /**
     * Array of the validation rules in the form (see laravel validation)
     *
     * @var array
     */
    protected $rules = array();

    /**
     * Insert an array of raw where clauses, the node will extends the query with
     * those extra where clauses.
     *
     * @var array
     */
    protected $filter = array();

    /**
     * The field in the table who is responsible for displaying the descriptor
     *
     * @var string
     */
    protected $descriptor = 'id';

    /**
     * The field and method who are responsible for the ordering
     *
     * @var array
     */
    protected $orderby = array('sortorder', 'asc');

    /**
     * If an node has an belongsTo relation (comment has post) the database
     * field of the table is needed
     *
     * @var null
     */
    protected $parent = null;


    /**
     * Function that is needed in the node, this descripbes how the node will
     * looks like, what it can/cannot do.
     *
     * @return mixed
     */
    abstract protected function configureFields();

    public function __construct()
    {
        $this->checkRequirements();

        $this->repo = new Repository(new $this->model);
        $this->repo->setRules($this->rules);
        $this->repo->setOrderby($this->orderby);
    }

    /**
     *  This method is always fired, this is the base of whole shapeshifter
     */
    protected function initAttributes()
    {
        $this->configureFields();
        $this->addTimestampFields();

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

        $records = $this->repo->getListRecords($this->orderby, $this->getParentInfo(), $this->filter);

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
            $this->data['id'] = $this->repo->save($this, $this->getParentInfo());
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

        if (method_exists($this, 'addDependencies'))
        {
            $this->addDependencies();
        }

        $user = Sentry::getUser();
        $user->setDisabledActions($this->disabledActions);

        $this->data['currentUser'] = $user;
        $this->data['orderBy'] = $this->orderby;
        $this->data['breadcrumbs'] = $breadcrumbService->breadcrumbs();
        $this->data['menu'] = $menuService->generateMenu();
        $this->data['tabs'] = $attributeService->attributesToTabs($this->mode, $this->attributes, $this->model);
        $this->data['descriptor'] = $this->getDescriptor();
        $this->data['cancel'] = $this->generateCancelLink();
        $this->data['disabledActions'] = $this->disabledActions;
        $this->data['disableDeleting'] = $this->disableDeleting;
        $this->data['disableEditing'] = $this->disableEditing;
        $this->data['model'] = $this->model;

        $this->data['lastVisibleAttribute'] = $this->getLastVisibleAttribute();
        $this->data['singular'] = $this->singular;
        $this->data['mode'] = $this->mode;
        $this->data['controller'] = get_class($this);
        $this->data['parent'] = $this->parent;
        $this->data['attributes'] = $this->attributes;

        array_map(function($attribute) {
            $attribute->compile();
        }, $this->data['attributes']);

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

            if ($this->mode == 'edit')
            {
                array_pop($parameters);
            }

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
     *  This function generates timestampfield when those fields
     *  doen't exist in the table of the model
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
        else {
            if ( ! class_exists($this->model) ) {
                throw new ClassNotExistException("Class [{$this->model}] doest not exist");
            }
            else {
                if ( (count($this->orderby) !== 2) || ($this->orderby[1] !== 'desc' && $this->orderby[1] !== 'asc') ) {
                    throw new PropertyNotExistException("Second property [orderby] must be `asc` or `desc`");
                }
            }
        }
    }

    /**
     * @return null
     */
    private function getLastVisibleAttribute()
    {
        $last = null;
        foreach ($this->attributes as $attribute) {
            if ( ! $attribute->hasFlag('hide_list') ) {
                $last = $attribute;
            }
        }

        return $last;
    }

    /**
     *  Dynamically add those timestampfields when allowd
     *
     */
    private function addTimestampFields()
    {
        if ( $this->allowTimestamps ) {
            $this->add(new Attribute\ReadonlyAttribute('updated_at', array('hide_add', 'hide_list')));
            $this->add(new Attribute\ReadonlyAttribute('created_at', array('hide_add', 'hide_list')));
        }
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
     * Trigger is fired before an new record is saved to the database
     *
     * @param $model
     * @return mixed
     */
    public function beforeAdd($model)
    {
        return $model;
    }

    /**
     * Trigger is fired after an new record is saved to the database
     *
     * @param $model
     * @return mixed
     */
    public function afterAdd($model)
    {
        return $model;
    }

    /**
     * Trigger is fired before an new record is updated to the database
     *
     * @param $model
     * @return mixed
     */
    public function beforeUpdate($model)
    {
        return $model;
    }

    /**
     * Trigger is fired after an record is updated to the database
     *
     * @param $model
     * @return mixed
     */
    public function afterUpdate($model)
    {
        return $model;
    }

    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes))
        {
            return $this->attributes[$key];
        }

        throw new InvalidArgumentException("Key [{$key}] doesnt exist in attributes list");
    }

    /**
     * Trigger is fired before an record will be deleted
     *
     * @param $model
     * @return mixed
     */
    public function beforeDestroy($model)
    {
        return $model;
    }
}

?>
