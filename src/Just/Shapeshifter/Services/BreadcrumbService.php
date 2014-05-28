<?php namespace Just\Shapeshifter\Services;

use Request;
use Route;

class BreadcrumbService {

    public function breadcrumbs()
    {
        $segments = Request::segments();
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

        $routes = Route::getRoutes();
        $route = $routes->getByName($name);

        $action = $route->getActionName();
        $controller = $this->getFullControllerPath($action);

        return new $controller();
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
