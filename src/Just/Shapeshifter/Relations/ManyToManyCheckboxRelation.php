<?php namespace Just\Shapeshifter\Relations;

use Just\Shapeshifter\Controllers as Controller;
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
class ManyToManyCheckboxRelation extends ManyToManyFacebookRelation {

    /**
     * display
     *
     * @access public
     * @throws \Just\Shapeshifter\Exceptions\MethodNotExistException
     * @return mixed Value.
     */
    public function display()
    {
        if ( ! $this->model ) return null;

        $this->checkDestinationModel();

        $descriptor = $this->destination->getDescriptor();
        $table = $this->destination->repo->getModel()->getTable();
        $results = $this->model->{$this->function}()->get(array($table . '.id', "{$descriptor} as name"))->lists('id');
        $all = $this->destination->repo->getModel()->get(array($table . '.id', "{$descriptor} as name"))->lists('name', 'id');

        return View::make('shapeshifter::relations.ManyToManyCheckboxRelation',  array(
            'results' => $results,
            'all' => $all,
            'name' => $this->name,
            'label' => translateAttribute($this->name)
        ));
    }

    /**
     * @param $val
     * @param null $oldValue
     * @return mixed|void
     */
    public function setAttributeValue($val, $oldValue = null)
    {
        if (is_array($val))
        {
            $this->value = array_keys($val);
        }else
        {
            parent::setAttributeValue($val, $oldValue);
        }
    }

}
?>
