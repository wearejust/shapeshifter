<?php namespace Just\Shapeshifter\Services;

use Illuminate\Foundation\Application;
use Just\Shapeshifter\Core\Models\Language;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\DatabaseManager as DB;

class BreadcrumbService {

    /**
     * @var Application
     */
    private $app;

	protected $languages;
	/**
	 * @var Config
	 */
	private $config;
	/**
	 * @var DB
	 */
	private $db;

	public function __construct(Application $app, Config $config, Language $languages, DB $db)
    {
        $this->app = $app;
	    $this->config = $config;
	    $this->languages = $languages;
	    $this->db = $db;
    }

    public function breadcrumbs()
    {
        $segments = $this->app['request']->segments();

        $path = '';
        $breadcrumbs = array();

        foreach ($segments as $k => $segment)
        {
            $path .= '/' . $segment;
            if ( $segment == 'edit' ) continue;

            list($edit, $mode) = $this->getTitle($segment, $segments, $k, $path);

            $breadcrumbs[] = array(
                'url'   => $path . $edit,
                'title' => $mode
            );
        }

        array_shift($breadcrumbs);

        return $breadcrumbs;
    }

    /**
     * resolveController
     *
     * @param mixed $url Description.
     * @param mixed $k Description.
     *
     * @access private
     * @return mixed Value.
     */
    private function resolveController($url, $k)
    {
        $verbs = array('create', 'edit');

        $new = array_filter($url, function ($item) use ($verbs) {
            return ! is_numeric($item) && ! in_array($item, $verbs);
        });

        $name = implode('.', array_slice($new, 0, ($k + 1))) . '.index';

        $routes = $this->app['router']->getRoutes();
        $route = $routes->getByName($name);

        $action = $route->getActionName();
        $controller = $this->getFullControllerPath($action);

        return $this->app->make($controller);
    }

    /**
     * @param $controller
     * @return mixed
     */
    private function getFullControllerPath($controller)
    {
        return head(explode('@', $controller));
    }

    /**
     * @param $segment
     * @param $url
     * @param $k
     * @param $path
     * @return array
     */
    private function getTitle($segment, $url, $k, $path)
    {
        $edit = '';
        if ( is_numeric($segment) ) {
            $edit = '/edit';

            $controller = $this->resolveController($url, ($k - 1));
            $record = $controller->repo->findById($segment);
            if ( is_object($record) ) {
                $mode = $record->{$controller->getDescriptor()};
	            $usesTranslation = preg_match('/translate./', $controller->getDescriptor());
	            if($usesTranslation)
	            {
		            $parts = explode('.', $controller->getDescriptor());
		            $regularDecriptor = array_last($parts,  function($key, $value)
		            {
			            return $value;
		            });

		            $defaultLanguage = $this->languages->remember(600)->where('short_code', '=', $this->config->get('app.locale'))->first(array('id'));
		            $table_name = $controller->repo->getTable();
		            $result     = $this->db->table($table_name . '_translations')
		                                   ->where('parent_id', '=', $record->id)
		                                   ->where('language_id', '=', $defaultLanguage->id)
		                                   ->where('attribute', '=', $regularDecriptor)
		                                   ->first();

		            if($result)
		            {
			            $mode = $result->value;
		            }
	            }
                return array($edit, $mode);
            }
        }
        else {
            switch ($segment) {
                case 'create':
                    $mode = __('form.create');
                    break;
                case 'admin':
                    $mode = 'Home';
                    break;
                default:
                    $controller = $this->resolveController($url, $k, $path);
                    $mode = $controller->getTitle();
                    break;
            }
        }

        return array($edit, $mode);
    }

}
