<?php

namespace Just\Shapeshifter\Core\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Controller;
use Just\Shapeshifter\Exceptions;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Repository;
use Just\Shapeshifter\ShapeshifterServiceProvider;
use Notification;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Event;

abstract class AdminController extends Controller
{
    const MODE_INDEX = 'index';
    const MODE_CREATE = 'create';
    const MODE_EDIT = 'edit';
    const MODE_UPDATE = 'update';
    const MODE_STORE = 'store';

    use AvailableOverrides;

    /**
     * @var Repository
     */
    private $repository;

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
     * If an node has an belongsTo relation (comment has post) the database
     * field of the table is needed
     *
     * @var null
     */
    protected $parent;

    /**
     * @var Form
     */
    private $formModifier;

    /**
     * @var string
     */
    private $viewNamespace = ShapeshifterServiceProvider::PACKAGE_NAMESPACE;

    /**
     * @var string
     */
    private $mode;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->formModifier = new Form();
        $this->repository = new Repository($this->formModifier, new $this->model);
    }

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
     * Build the query used to get an collection of items to use in the
     * list (table) of items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    abstract protected function indexQuery(Builder $query);

    /**
     * Returns string with title of the module
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function index()
    {
        $this->mode = self::MODE_INDEX;
        $this->configureFields($this->formModifier);

        return $this->setupView('index', [
            'records' => $this->indexQuery($this->repository->getNewQuery()),
            'title'   => $this->getTitle(),
            'ids'     => func_get_args(),
            'model'   => $this->repository->getNew(),
        ]);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $this->mode = self::MODE_CREATE;
        $this->configureFields($this->formModifier);

        return $this->setupView('form.create', [
            'title' => $this->getTitle(),
            'ids'   => func_get_args(),
            'model' => $this->repository->getNew(),
        ]);
    }

    /**
     * @return mixed
     */
    public function edit()
    {
        $this->mode = self::MODE_EDIT;
        $this->configureFields($this->formModifier);
        $ids = func_get_args();

        return $this->setupView('form.edit', [
            'ids'   => $ids,
            'title' => $this->getTitle(),
            'model' => $this->repository->findById(last($ids)),
        ]);
    }

    /**
     * @return mixed
     */
    public function store()
    {
        $this->mode = self::MODE_STORE;
        $this->configureFields($this->formModifier);

        try {
            $model = $this->repository->store($this->rules);
            $model = $this->afterAdd($model);
        } catch (Exceptions\ValidationException $e) {
            $this->addErrorsToFlash($e->getErrors()->all());

            return redirect()->back()->withInput();
        } catch (QueryException $e) {
            $this->addErrorsToFlash($e->getMessage());

            return redirect()->back()->withInput();
        }

        Notification::success(__('form.stored'));

        Event::fire('shapeshifter.eloquent.created: ' . get_class($model), [$model)]);

        return $this->redirectAfterStore($this->getRedirectRoute(), func_get_args(), $model->id);
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $this->mode = self::MODE_UPDATE;
        $this->configureFields($this->formModifier);

        $ids = func_get_args();
        $model = $this->repository->findById(last($ids));

        try {
            $model = $this->repository->save($model, $this->rules);
            $model = $this->afterUpdate($model);

        } catch (Exceptions\ValidationException $e) {
            $this->addErrorsToFlash($e->getErrors()->all());

            return redirect()->back()->withInput();
        } catch (QueryException $e) {
            $this->addErrorsToFlash($e->getMessage());

            return redirect()->back()->withInput();
        }

        Notification::success(__('form.updated'));

        Event::fire('shapeshifter.eloquent.updated: ' . get_class($model), [$model)]);

        return $this->redirectAfterUpdate($this->getRedirectRoute(), $ids, $model->id);
    }

    /**
     * @return mixed
     */
    public function destroy()
    {
        $this->configureFields($this->formModifier);
        $ids = func_get_args();

        $model = $this->repository->findById(last($ids));

        $modelId = $model->getKey();

        if ($this->repository->delete($model)) {
            Notification::success(__('form.removed'));
        }

        Event::fire('shapeshifter.eloquent.deleted: ' . get_class($model), [$model, $modelId]);

        return $this->redirectAfterDestroy($this->getRedirectRoute(), $ids);
    }

    /**
     * @param       $template
     *
     * @param array $data
     *
     * @return mixed
     */
    private function setupView($template, array $data = [])
    {
        $user = Sentinel::getUser();
        $user->setDisabledActions($this->disabledActions);

        $this->formModifier->render();

        return view()->make("{$this->viewNamespace}::{$template}", array_merge($data, [
            'form'                 => $this->formModifier,
            'attributes'           => $this->formModifier->getAllAttributes(),
            'lastVisibleAttribute' => $this->formModifier->getLastVisibleAttribute(),
            'disabledActions'      => $this->disabledActions,
            'disableDeleting'      => $this->disableDeleting,
            'disableEditing'       => $this->disableEditing,
            'parent'               => $this->parent,
            'mode'                 => $this->mode,
            'routes'               => $this->getCurrentRouteNames(),
        ]));
    }

    /**
     * @return array
     */
    private function getCurrentRouteNames()
    {
        $verbs = ['update', 'edit', 'index', 'destroy', 'create', 'store'];
        $regex = sprintf('/\.(%s)/', implode('|', $verbs));
        $current = preg_replace($regex, '', $this->getRouter()->currentRouteName());

        return array_map(function ($item) use ($current) {
            return $current.'.'.$item;
        }, array_combine($verbs, $verbs));
    }

    /**
     * @param null $route
     *
     * @return string
     */
    private function getRedirectRoute($route = null)
    {
        $routes = $this->getCurrentRouteNames();

        $route = $route ?: $routes['index'];
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
     * @param $e
     */
    private function addErrorsToFlash($e)
    {
        array_map(function ($item) {
            Notification::error($item);
        }, (array)$e);
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return \Just\Shapeshifter\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
