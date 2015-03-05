<?php namespace Just\Shapeshifter\Relations;

use Just\Shapeshifter\Controllers as Controller;
use Just\Shapeshifter\Exceptions\MethodNotExistException;
use Request;
use Route;
use Str;
use View;

/**
* OneToManyRelation
*
* @uses     Relation
*
* @category Category
* @package  Package
* @author   JUST BV
* @link     http://wearejust.com/
*/
class ManyToManyFacebookRelation extends OneToManyRelation
{

    /**
     * @param string $fromcontroller
     * @param array $destination
     * @param $function
     * @param array $flags
     * @throws \Just\Shapeshifter\ShapeShifterException
     */
    public function __construct($fromcontroller, $destination, $function, $flags = array())
	{
        $routes = Route::getRoutes();

        $this->destination = 'admin.' . $destination . '.index';
        $this->destination = $this->resolveControllerByName($routes);

        $this->fromcontroller = $fromcontroller;

        if ($current = $this->getCurrentRecordId() )
        {
            $this->model = $fromcontroller->repo->findById($current);
        }

        $this->function = $function;
        $this->name = $this->destination->getTitle();

        $this->flags = $flags;
		$this->flags[] = 'hide_list';
	}

    /**
     * display
     *
     * @access public
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     * @return mixed Value.
     */
	public function compile()
	{
        if (! $this->model) return null;

        $this->checkDestinationModel();

        $descriptor = $this->destination->getDescriptor();

        $table = $this->destination->repo->getModel()->getTable();
        $results = $this->model->{$this->function}()->get(array($table.'.id',"{$descriptor} as name"))->toJson();
        $all = $this->destination->repo->getModel()->get(array($table.'.id',"{$descriptor} as name"))->toJson();

        $this->html = View::make('shapeshifter::relations.ManyToManyFacebookRelation',  array(
            'results' => $results,
            'all' => $all,
            'name' => $this->name,
            'label' => translateAttribute($this->name)
        ))->render();
    }

    /**
     * @param $val
     * @param null $oldValue
     * @return mixed|void
     */
    public function setAttributeValue($val, $oldValue = null)
    {

        if(is_array($val)) { $val = array_shift($val);}

        $this->value = $val ? explode(',', $val) : array();
    }

    /**
     * @return null
     */
    public function getSaveValue()
    {
        if (! $this->model) return;

        $this->model->{$this->function}()->withTimestamps()->sync($this->value);

        return null;
    }

    /**
     * @return bool|int
     */
    protected function getCurrentRecordId()
    {
        $segments = array_reverse(Request::segments());

        foreach ($segments as $seg) {
            if (is_numeric($seg)) return (int)$seg;
        }

        return false;
    }

    protected function checkDestinationModel()
    {
        if ( ! method_exists($this->model, $this->function) ) {
            $modelName = get_class($this->model);

            throw new MethodNotExistException("Relation method [{$this->function}] doest not exist on [{$modelName}] model");
        }
    }
}

?>
