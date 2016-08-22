<?php

namespace Just\Shapeshifter\Core\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Just\Shapeshifter\Attributes\MediumAttribute;
use Just\Shapeshifter\Exceptions;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Repository;
use Notification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AdminController extends Controller
{
    use AvailableOverrides;

    /**
     * @var Repository
     */
    private $repo;

    /**
     * The mode of the current action (create, edit)
     *
     * @var string
     */
    private $mode;

    /**
     * This data array holds all the data that will be send to the view
     *
     * @var array
     */
    protected $data = [];

    /**
     * Disable some actions in the node (drag, sort, create, delete)
     *
     * @var array
     */
    protected $paginate = false;

    /**
     * Disable some actions in the node (drag, sort, create, delete)
     *
     * @var array
     */
    protected $disabledActions = [];

    /**
     * Array of record ids which cannot be deleted in the node
     *
     * @var array
     */
    protected $disableDeleting = [];

    /**
     * Array of record ids which cannot be edited in the node
     *
     * @var array
     */
    protected $disableEditing = [];

    /**
     * Array of the validation rules in the form (see laravel validation)
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Insert an array of raw where clauses, the node will extends the query with
     * those extra where clauses.
     *
     * @var array
     */
    protected $filter = [];

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
    protected $orderby = ['sortorder', 'asc'];

    /**
     * If an node has an belongsTo relation (comment has post) the database
     * field of the table is needed
     *
     * @var null
     */
    protected $parent;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Form
     */
    protected $formModifier;

    /**
     * @var string
     */
    protected $singular = 'Pagina';

    /**
     * @var string
     */
    protected $plural = 'Paginas';

    /**
     * @var string
     */
    protected $viewNamespace = 'shapeshifter';

    /**
     * Function that is needed in the node, this descripbes how the node will
     * looks like, what it can/cannot do.
     *
     * @param Form $modifier
     *
     * @return Form
     */
    abstract protected function configureFields(Form $modifier);

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->checkRequirements();

        $this->repo = $this->app->make(Repository::class, [new $this->model(), $app]);
        $this->repo->setOrderby($this->orderby);
    }

    /**
     *  This method is always fired, this is the base of whole shapeshifter
     */
    public function initAttributes()
    {
        $this->formModifier = $this->app->make(Form::class, [$this->mode]);

        $this->beforeInit($this->formModifier);
        $this->configureFields($this->formModifier);
        $this->afterInit($this->formModifier);

        $this->repo->setRules($this->rules);
        $this->repo->setAttributes($this->formModifier->getAllAttributes(), $this->repo->getRules());

        $this->data['routes'] = $this->getCurrentRouteNames();
    }

    /**
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function index()
    {
        $this->data['ids'] = func_get_args();

        $this->mode  = 'index';
        $this->model = $this->repo->getNew();

        $this->initAttributes();

        $records = $this->repo->all($this->orderby, $this->filter, $this->getParentInfo(), $this->paginate);

        if (!count($records) && $this->app['request']->ajax() && in_array('create', $this->disabledActions)) {
            throw new NotFoundHttpException('No records, No ability to create and Ajax request');
        }

        $this->data['title']   = $this->plural;
        $this->data['records'] = $records;

        return $this->setupView('index');
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $this->data['ids'] = func_get_args();

        $this->mode  = 'create';
        $this->model = $this->repo->getNew();

        $this->initAttributes();

        $this->data['title']  = $this->singular . ' ' . strtolower(__('form.create'));
        $this->data['parent'] = $this->getParentInfo();

        return $this->setupView('form');
    }

    /**
     * @return mixed
     */
    public function edit()
    {
        $this->data['ids'] = func_get_args();

        $this->mode  = 'edit';
        $this->model = $this->repo->findById($this->getCurrentId());

        $this->data['title'] = $this->descriptor === 'id' ? $this->singular . ' bewerken' : strip_tags(translateAttribute($this->model->{$this->descriptor}));

        $this->initAttributes();

        return $this->setupView('form');
    }

    /**
     * @return mixed
     */
    public function store()
    {
        $this->data['ids'] = func_get_args();

        $this->mode  = 'store';
        $this->model = $this->repo->getNew();

        $this->initAttributes();

        try {
            $this->repo->setModel($this->model);

            $this->data['id'] = $this->repo->save($this, $this->getParentInfo());
        } catch (Exceptions\ValidationException $e) {
            array_map(function ($item) {
                Notification::error($item);
            }, $e->getErrors()->all());

            return $this->app['redirect']->back()->withInput();
        }

        Notification::success(__('form.stored'));

        return $this->redirectAfterStore($this->getRedirectRoute(), $this->data['ids'], $this->data['id']);
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $this->data['ids'] = func_get_args();

        $this->mode  = 'update';
        $this->model = $this->repo->findById($this->getCurrentId());

        $this->initAttributes();

        try {
            $this->repo->setModel($this->model);

            $this->data['id'] = $this->repo->save($this);
        } catch (Exceptions\ValidationException $e) {
            array_map(function ($item) {
                Notification::error($item);
            }, $e->getErrors()->all());

            return $this->app['redirect']->back()->withInput();
        }

        Notification::success(__('form.updated'));

        return $this->redirectAfterUpdate($this->getRedirectRoute(), $this->data['ids'], $this->data['id']);
    }

    /**
     * @return mixed
     */
    public function destroy()
    {
        $this->data['ids'] = func_get_args();

        $this->mode  = 'destroy';
        $this->model = $this->repo->findById($this->getCurrentId());

        $this->initAttributes();

        $this->model = $this->beforeDestroy($this->model);

        if ($this->repo->delete($this->model)) {
            Notification::success(__('form.removed'));
        }

        return $this->redirectAfterDestroy($this->getRedirectRoute(), $this->data['ids']);
    }

    /**
     * @param $template
     *
     * @return mixed
     */
    protected function setupView($template)
    {
        $user = Sentinel::getUser();
        $user->setDisabledActions($this->disabledActions);

        $this->formModifier->render();

        $this->beforeRender($this);

        return $this->app['view']->make("{$this->viewNamespace}::{$template}", array_merge($this->data, [
            'form'                 => $this->formModifier,
            'attributes'           => $this->repo->setAttributeValues($this->mode, $this->formModifier->getAllAttributes(), $this->model),
            'orderBy'              => $this->orderby,
            'cancel'               => $this->generateCancelLink(),
            'disabledActions'      => $this->disabledActions,
            'disableDeleting'      => $this->disableDeleting,
            'disableEditing'       => $this->disableEditing,
            'model'                => $this->model,
            'lastVisibleAttribute' => $this->getLastVisibleAttribute(),
            'singular'             => $this->singular,
            'plural'               => $this->plural,
            'paginate'             => $this->paginate,
            'mode'                 => $this->mode,
            'controller'           => get_class($this),
            'parent'               => $this->parent,
            'filters'              => $this->filter
        ]));
    }

    /**
     * @return array
     */
    public function getCurrentRouteNames()
    {
        $verbs   = ['update', 'edit', 'index', 'destroy', 'create', 'store'];
        $regex   = sprintf('/\.(%s)/', implode('|', $verbs));
        $current = preg_replace($regex, '', $this->app['router']->currentRouteName());

        return array_map(function ($item) use ($current) {
            return $current . '.' . $item;
        }, array_combine($verbs, $verbs));
    }

    /**
     * @return mixed
     */
    protected function getCurrentId()
    {
        return last($this->data['ids']);
    }

    /**
     * @return array
     */
    public function getParentInfo()
    {
        if ($this->parent) {
            //lame
            $segs = array_reverse($this->app['request']->segments());

            foreach ($segs as $seg) {
                if (is_numeric($seg)) {
                    return [$this->parent, (int) $seg];
                }
            }
        }

        return [];
    }

    /**
     * @return string
     */
    private function generateCancelLink()
    {
        if (!array_key_exists('ids', $this->data)) {
            return [];
        }

        $parameters = $this->data['ids'];

        if ($this->repo->hasParent($this->getParentInfo())) {
            $edit = explode('.', $this->data['routes']['edit']);
            unset($edit[count($edit) - 2]);

            if ($this->mode == 'edit') {
                array_pop($parameters);
            }

            return route(implode('.', $edit), $parameters);
        }

        return route($this->data['routes']['index'], $parameters);
    }

    /**
     * @throws Exceptions\ClassNotExistException
     * @throws Exceptions\PropertyNotExistException
     */
    private function checkRequirements()
    {
        if (!$this->singular || !$this->plural || !$this->model) {
            throw new Exceptions\PropertyNotExistException('Property [singular] or [plural] or [model] does not exist');
        }

        if (!class_exists($this->model)) {
            throw new Exceptions\ClassNotExistException(sprintf('Class [%s] doesn\'t exist', $this->model));
        }
    }

    /**
     * @return null
     */
    private function getLastVisibleAttribute()
    {
        $last = null;
        foreach ($this->formModifier->getAllAttributes() as $attribute) {
            if (!$attribute->hasFlag('hide_list')) {
                $last = $attribute;
            }
        }

        return $last;
    }

    /**
     * @return string
     */
    private function getRedirectRoute($route = null)
    {
        $route = $route ?: $this->data['routes']['index'];
        if ($this->parent) {
            $route = explode('.', $route);
            array_pop($route);
            array_pop($route);
            array_push($route, 'edit');
            $route = implode('.', $route);
        }

        return $route;
    }

    /**
     * @param $name
     *
     * @return null
     */
    public function mediumEditor($name)
    {
        $this->formModifier->tab($name, function ($tab) {
            $tab->add(new MediumAttribute('content'));
        });
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
        return str_replace(' ', '_', $this->plural);
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
     * @return null|string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return Repository
     */
    public function getRepo()
    {
        return $this->repo;
    }
}
