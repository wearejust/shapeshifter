<?php

namespace Just\Shapeshifter\Services;

use Illuminate\Foundation\Application;

class BreadcrumbService
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return array
     */
    public function breadcrumbs()
    {
        $segments = $this->app['request']->segments();

        $path        = '';
        $breadcrumbs = [];

        foreach ($segments as $k => $segment) {
            $path .= '/' . $segment;
            if ($segment == 'edit') {
                continue;
            }

            list($edit, $mode) = $this->getTitle($segment, $segments, $k, $path);

            $breadcrumbs[] = [
                'url'   => $path . $edit,
                'title' => str_replace("_", " ", $mode),
            ];
        }

        array_shift($breadcrumbs);

        return $breadcrumbs;
    }

    /**
     * resolveController
     *
     * @param mixed $url Description.
     * @param mixed $k   Description.
     *
     * @access private
     *
     * @return mixed Value.
     */
    private function resolveController($url, $k)
    {
        $verbs = ['create', 'edit'];

        $new = array_filter(array_flip($url), function ($key) use ($url, $verbs, $k) {
            $item = $url[$key];

            return $key <= $k && ! is_numeric($item) && ! in_array($item, $verbs);
        });
        $new = array_flip($new);

        $name = implode('.', array_slice($new, 0, ($k + 1))) . '.index';

        $routes = $this->app['router']->getRoutes();
        $route  = $routes->getByName($name);


        if($route ===null) {
            return null;
        }
        $action     = $route->getActionName();
        $controller = $this->getFullControllerPath($action);

        return $this->app->make($controller);
    }

    /**
     * @param $controller
     *
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
     *
     * @return array
     */
    private function getTitle($segment, $url, $k, $path)
    {
        $edit = '';
        if (is_numeric($segment)) {
            $edit = '/edit';

            $controller = $this->resolveController($url, ($k - 1));
            $record     = $controller->getRepo()->findById($segment);
            if (is_object($record)) {
                $mode = $record->{$controller->getDescriptor()};

                return [$edit, $mode];
            }
        } else {
            switch ($segment) {
                case 'create':
                    $mode = __('form.create');
                    break;
                case 'admin':
                    $mode = 'Home';
                    break;
                default:
                    $controller = $this->resolveController($url, $k, $path);
                    if($controller === null) {
                        return null;
                    }
                    $mode       = $controller->getTitle();
                    break;
            }
        }

        return [$edit, $mode];
    }
}
