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

abstract class AdminController extends Controller
{
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
    private $disabledActions = [];

    /**
     * Array of record ids which cannot be deleted in the node
     *
     * @var array
     */
    private $disableDeleting = [];

    /**
     * Array of record ids which cannot be edited in the node
     *
     * @var array
     */
    private $disableEditing = [];

    /**
     * Array of the validation rules in the form (see laravel validation)
     *
     * @var array
     */
    private $rules = [];

    /**
     * If an node has an belongsTo relation (comment has post) the database
     * field of the table is needed
     *
     * @var null
     */
    private $parent;

    /**
     * @var Form
     */
    private $formModifier;

    /**
     * @var string
     */
    private $viewNamespace = ShapeshifterServiceProvider::PACKAGE_NAMESPACE;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->formModifier = new Form();
        $this->repository = new Repository($this->formModifier, new $this->model);

        $this->configureFields($this->formModifier);
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
     * @throws NotFoundHttpException
     *
     * @return mixed
     */
    public function index()
    {

        return $this->setupView('index', [
            'records' => $this->indexQuery($this->repository->getNewQuery()),
            'title'   => 'dfgdfgasdfghjkl',
            'ids'     => func_get_args(),
            'model'   => $this->repository->getNew(),
        ]);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        return $this->setupView('form.create', [
            'title' => 'dfgdsdfdsffgasdfghjkl',
            'ids'   => func_get_args(),
            'model' => $this->repository->getNew(),
        ]);
    }

    /**
     * @return mixed
     */
    public function edit()
    {
        $ids = func_get_args();

        return $this->setupView('form.edit', [
            'ids'   => $ids,
            'title' => 'gjgjghjfghfhjgk',
            'model' => $this->repository->findById(last($ids)),
        ]);
    }

    /**
     * @return mixed
     */
    public function store()
    {
        try {
            $model = $this->repository->store($this->rules);
        } catch (Exceptions\ValidationException $e) {
            $this->addErrorsToFlash($e->getErrors()->all());

            return redirect()->back()->withInput();
        } catch (QueryException $e) {
            $this->addErrorsToFlash($e->getMessage());

            return redirect()->back()->withInput();
        }

        Notification::success(__('form.stored'));

        return $this->redirectAfterStore($this->getRedirectRoute(), func_get_args(), $model->id);
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $ids = func_get_args();
        $model = $this->repository->findById(last($ids));

        try {
            $model = $this->repository->update($model, $this->rules);
        } catch (Exceptions\ValidationException $e) {
            $this->addErrorsToFlash($e->getErrors()->all());

            return redirect()->back()->withInput();
        } catch (QueryException $e) {
            $this->addErrorsToFlash($e->getMessage());

            return redirect()->back()->withInput();
        }

        Notification::success(__('form.updated'));

        return $this->redirectAfterUpdate($this->getRedirectRoute(), $ids, $model->id);
    }

    /**
     * @return mixed
     */
    public function destroy()
    {
        $ids = func_get_args();
        $model = $this->repository->findById(last($ids));

        if ($this->repository->delete($model)) {
            Notification::success(__('form.removed'));
        }

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

}
