<?php namespace Just\Shapeshifter\Core\Models;

class Group extends \Cartalyst\Sentry\Groups\Eloquent\Group
{
    protected $table = 'cms_groups';
    protected $hidden = array('pivot');
}
