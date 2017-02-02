<?php

namespace Just\Shapeshifter\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Config\Repository;
use Illuminate\Routing\Controller;
use Just\Shapeshifter\Menu\Sidebar\Menuitem;

class ModuleController extends Controller
{
    use Helpers;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->config->get('shapeshifter.menu');
    }

    /**
     * @param $module
     *
     * @return mixed
     */
    public function show($module)
    {
        return array_first($this->config->get('shapeshifter.menu'), function(Menuitem $a) use ($module) {
            return $a->getModule() === $module;
        });
    }
}
