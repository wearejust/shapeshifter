<?php namespace Just\Shapeshifter\Core\Models;

class Throttle extends \Cartalyst\Sentry\Throttling\Eloquent\Throttle
{
    protected $table = 'cms_throttle';
}
