<?php namespace Just\Shapeshifter\Core\Controllers;

use Just\Shapeshifter\Attributes as Attribute;
use Just\Shapeshifter\Form\Form;
use Just\Shapeshifter\Relations as Relation;

class SettingsController extends AdminController
{
    protected $singular = "Setting";
    protected $plural = "Settings";

    protected $model = 'Just\Shapeshifter\Core\Models\Settings';
    protected $descriptor = "id";
    protected $orderby = array('id','desc');
    protected $disabledActions = array(
        'delete',
        'drag'
    );

    protected function configureFields(Form $modifier)
    {
	    $modifier->add( new Attribute\TextAttribute('key', 'key'));
	    $modifier->add( new Attribute\TextAttribute('value', 'value'));
    }

}

?>
