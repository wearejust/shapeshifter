<?php namespace Just\Shapeshifter\Core\Controllers;

use Controller;
use Illuminate\Foundation\Application;
use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Core\Models\Language;
use Just\Shapeshifter\Exceptions\ClassNotExistException;
use Just\Shapeshifter\Exceptions\PropertyNotExistException;
use Just\Shapeshifter\Exceptions\ValidationException;
use Just\Shapeshifter\Form\AttributeCollection;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Form\Section;
use Just\Shapeshifter\Form\Tab;
use Notification;
use Config;

use Sentry;
use Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AdminController extends Controller
{

	/**
	 * @var \Just\Shapeshifter\Repository
	 */
	public $repo;

	/**
	 * The mode of the current action (create, edit)
	 *
	 * @var
	 */
	public $mode;

	/**
	 * This data array holds all the data that will be send to the view
	 *
	 * @var array
	 */
	protected $data = array();

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
	 * Enable preview mode button
	 *
	 * @var bool
	 */
	protected $preview = false;

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Form
	 */
	protected $formModifier;

	/**
	 * @var
	 */
	protected $languages;

	/**
	 * @var
	 */
	protected $active_lang;

	/**
	 * @var
	 */
	public $addBlocks;


	/**
	 * Function that is needed in the node, this descripbes how the node will
	 * looks like, what it can/cannot do.
	 *
	 * @return mixed
	 */
	abstract protected function configureFields (Form $modifier);

	public function __construct (Application $app)
	{
		$this->app = $app;

		$this->checkRequirements();

		$this->repo = $this->app->make(
			'Just\Shapeshifter\Repository', array(new $this->model, $this->app)
		);

		$this->setLanguageAttributes();

		$this->data['addBlocks'] = $this->addBlocks;

		$this->repo->setOrderby($this->orderby);
	}

	/**
	 *  This method is always fired, this is the base of whole shapeshifter
	 */
	private function initAttributes ()
	{
		$this->formModifier = $this->app->make('Just\Shapeshifter\Form\Form');
		$this->formModifier->setMode($this->mode);

		$this->beforeInit($this->formModifier);
		$this->configureFields($this->formModifier);
		$this->afterInit($this->formModifier);

		$this->repo->setRules($this->rules);
		$this->repo->setAttributes($this->formModifier->getAllAttributes(), $this->repo->getRules());

		$this->data['routes'] = $this->getCurrentRouteNames();
	}

	/**
	 * @throws NotFoundHttpException
	 * @return mixed
	 */
	public function index ()
	{
		if (!$this->userHasAccess())
		{
			return $this->setupView('no_access');
		}

		$this->data['ids'] = func_get_args();

		$this->mode  = 'index';
		$this->model = $this->repo->getNew();

		$this->initAttributes();
		$this->generateTimestampFields();

		$records = $this->repo->all($this->orderby, $this->filter, $this->getParentInfo());

		if ($this->app['request']->ajax() && !count($records) && in_array('create', $this->disabledActions))
		{
			throw new NotFoundHttpException('No records, No ability to create and Ajax request');
		}

		$this->data['title']   = $this->plural;
		$this->data['records'] = $records;

		return $this->setupView('index');
	}

	/**
	 * @return mixed
	 */
	final public function create ()
	{
		if (!$this->userHasAccess())
		{
			return $this->setupView('no_access');
		}

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
	final public function edit ()
	{
		if (!$this->userHasAccess())
		{
			return $this->setupView('no_access');
		}

		$this->data['ids'] = func_get_args();

		$this->mode  = 'edit';
		$this->model = $this->repo->findById($this->getCurrentId());

		$this->data['title'] = $this->getDescriptor() == 'id' ? $this->singular . ' bewerken' : strip_tags(translateAttribute($this->model->{$this->getDescriptor()}));

		$this->initAttributes();

		return $this->setupView('form');
	}

	/**
	 * @return mixed
	 */
	final public function store ()
	{
		if (!$this->userHasAccess())
		{
			return $this->setupView('no_access');
		}

		$this->data['ids'] = func_get_args();

		$this->mode  = 'store';
		$this->model = $this->repo->getNew();

		$this->initAttributes();

		try
		{
			$this->data['id'] = $this->repo->save($this, $this->getParentInfo());
			$this->repo->save($this, $this->getParentInfo());
		} catch (ValidationException $e)
		{
			Notification::error($e->getErrors()->all());

			return $this->app['redirect']->back()->withInput();
		}

		Notification::success(__('form.stored'));

		return $this->redirectAfterStore($this->data['routes']['index'], $this->data['ids'], $this->data['id']);
	}

	/**
	 * @return mixed
	 */
	final public function update ()
	{
		if (!$this->userHasAccess())
		{
			return $this->setupView('no_access');
		}

		$this->data['ids'] = func_get_args();

		$this->mode  = 'update';
		$this->model = $this->repo->findById($this->getCurrentId());

		$this->initAttributes();

		try
		{
			$this->data['id'] = $this->repo->save($this);

		} catch (ValidationException $e)
		{
			Notification::error($e->getErrors()->all());

			return $this->app['redirect']->back()->withInput();
		}

		Notification::success(__('form.updated'));

		return $this->redirectAfterUpdate($this->data['routes']['index'], $this->data['ids'], $this->data['id']);
	}

	/**
	 * @return mixed
	 */
	final public function destroy ()
	{
		if (!$this->userHasAccess())
		{
			return $this->setupView('no_access');
		}

		$this->data['ids'] = func_get_args();

		$this->mode  = 'destroy';
		$this->model = $this->repo->findById($this->getCurrentId());

		$this->initAttributes();

		$this->model = $this->beforeDestroy($this->model);

		if ($this->repo->delete())
		{
			Notification::success(__('form.removed'));
		}

		return $this->redirectAfterDestroy($this->data['routes']['index'], $this->data['ids']);
	}

	/**
	 * @param $template
	 *
	 * @return mixed
	 */
	protected function setupView ($template)
	{
		$breadcrumbService = $this->app->make('Just\Shapeshifter\Services\BreadcrumbService');
		$menuService       = $this->app->make('Just\Shapeshifter\Services\MenuService');

		$user = Sentry::getUser();
		$user->setDisabledActions($this->disabledActions);

		$this->formModifier->render();

		$this->data['form']            = $this->formModifier;
		$this->data['attributes']      = $this->repo->setAttributeValues($this->mode, $this->formModifier->getAllAttributes(), $this->model);
		$this->data['currentUser']     = $user;
		$this->data['orderBy']         = $this->orderby;
		$this->data['breadcrumbs']     = (Config::get('shapeshifter::config.breadcrumbs')) ? $breadcrumbService->breadcrumbs() : array();
		$this->data['menu']            = $menuService->generateMenu();
		$this->data['descriptor']      = $this->getDescriptor();
		$this->data['cancel']          = $this->generateCancelLink();
		$this->data['disabledActions'] = $this->disabledActions;
		$this->data['disableDeleting'] = $this->disableDeleting;
		$this->data['disableEditing']  = $this->disableEditing;
		$this->data['model']           = $this->model;
		$this->data['preview']         = $this->preview;

		$this->data['lastVisibleAttribute'] = $this->getLastVisibleAttribute();
		$this->data['singular']             = $this->singular;
		$this->data['mode']                 = $this->mode;
		$this->data['controller']           = get_class($this);
		$this->data['parent']               = $this->parent;

		$node = $this;

		$this->beforeRender($node);

		$view = $this->app['view']->make("shapeshifter::{$template}", $this->data);

		return $this->app->make('Illuminate\Http\Response', array($view, 200, array(
			'Expires'       => 'Tue, 1 Jan 1980 00:00:00 GMT',
			'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
			'Pragma'        => 'no-cache',
		)));
	}

	/**
	 * @return array
	 */
	private function getCurrentRouteNames ()
	{
		$verbs   = array('update', 'edit', 'index', 'destroy', 'create', 'store');
		$current = $this->getCurrentRouteName();

		foreach ($verbs as $verb)
		{
			$destinations[$verb] = $current . '.' . $verb;
		}

		return $destinations;
	}

	/**
	 * @return bool
	 */
	protected function userHasAccess ()
	{
		return Sentry::getUser()->isSuperUser() || Sentry::getUser()->hasAnyAccess(array($this->app['router']->currentRouteName()));
	}

	/**
	 * @return mixed
	 */
	protected function getCurrentId ()
	{
		return last($this->data['ids']);
	}

	/**
	 * @return array
	 */
	private function getParentInfo ()
	{
		if ($this->parent)
		{
			//lame
			$segs = array_reverse($this->app['request']->segments());

			foreach ($segs as $seg)
			{
				if (is_numeric($seg)) return array($this->parent, (int)$seg);
			}
		}

		return array();
	}

	/**
	 * @return string
	 */
	private function generateCancelLink ()
	{
		if (!isset($this->data['ids'])) return array();

		$parameters = $this->data['ids'];

		if ($this->repo->hasParent($this->getParentInfo()))
		{
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
	private function getCurrentRouteName ()
	{
		$current = $this->app['router']->currentRouteName();
		$current = explode('.', $current);

		array_pop($current);

		return implode('.', $current);
	}

	/**
	 *  This function generates timestampfield when those fields
	 *  doen't exist in the table of the model
	 *
	 */
	private function generateTimestampFields ()
	{
		$this->app->make('Just\Shapeshifter\Helpers\TimestampHelper', array($this->repo->getTable()))
		          ->createFields();
	}

	/**
	 * @throws \Exception
	 * @throws \Just\Shapeshifter\Exceptions\ClassNotExistException
	 * @throws \Just\Shapeshifter\Exceptions\PropertyNotExistException
	 */
	private function checkRequirements ()
	{
		if (!array_key_exists('Just\Shapeshifter\ShapeshifterServiceProvider', $this->app->getLoadedProviders()))
		{
			throw new \Exception("Did you forgot to load the ShapeShifter service provider in [config/app.php]?");
		} elseif (!isset($this->singular) || !isset($this->plural) || !isset($this->model))
		{
			throw new PropertyNotExistException("Property [singular] or [plural] or [model] does not exist");
		} else
		{
			if (!class_exists($this->model))
			{
				throw new ClassNotExistException("Class [{$this->model}] doest not exist");
			} else
			{
				if ((count($this->orderby) !== 2) || ($this->orderby[1] !== 'desc' && $this->orderby[1] !== 'asc'))
				{
					throw new PropertyNotExistException("Second property [orderby] must be `asc` or `desc`");
				}
			}
		}
	}

	/**
	 * @return null
	 */
	private function getLastVisibleAttribute ()
	{
		$last = null;
		foreach ($this->formModifier->getAllAttributes() as $attribute)
		{
			if (!$attribute->hasFlag('hide_list'))
			{
				$last = $attribute;
			}
		}

		return $last;
	}


	protected function redirectAfterUpdate ($route, $args, $currentId)
	{
		return $this->app['redirect']->route($route, $args);
	}

	protected function redirectAfterStore ($route, $args, $currentId)
	{
		return $this->app['redirect']->route($route, $args);
	}

	protected function redirectAfterDestroy ($route, $args)
	{
		return $this->app['redirect']->route($route, $args);
	}

	protected function beforeInit (Form $modifier)
	{
		//
	}

	protected function afterInit (Form $modifier)
	{
		//
	}

	/**
	 * @param $node
	 */
	public function beforeRender ($node) { }

	/**
	 * @return string
	 */
	public function getModel ()
	{
		return $this->model;
	}

	/**
	 * @return string
	 */
	public function getTitle ()
	{
		return $this->plural;
	}

	/**
	 * @return string
	 */
	public function getDescriptor ()
	{
		return $this->descriptor;
	}

	/**
	 * @return array
	 */
	public function getOrderBy ()
	{
		return $this->orderby;
	}

	/**
	 * Trigger is fired before an new record is saved to the database
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function beforeAdd ($model)
	{
		return $model;
	}

	/**
	 * Trigger is fired after an new record is saved to the database
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function afterAdd ($model)
	{
		return $model;
	}

	/**
	 * Trigger is fired before an new record is updated to the database
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function beforeUpdate ($model)
	{
		return $model;
	}

	/**
	 * Trigger is fired after an record is updated to the database
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function afterUpdate ($model)
	{
		return $model;
	}

	/**
	 * Trigger is fired before an record will be deleted
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function beforeDestroy ($model)
	{
		return $model;
	}

	/**
	 * @return null
	 */
	public function getParent ()
	{
		return $this->parent;
	}

	private function setLanguageAttributes ()
	{
		if(\Schema::hasTable('languages'))
		{
			$this->languages   = Language::where('active', '=', 1)->remember(20)->orderBy('sortorder')->get();
			$this->active_lang = Session::get('active_lang');
		}
	}
}

?>
