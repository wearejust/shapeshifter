<?php namespace Just\Shapeshifter\Core\Models;

class Settings extends \Eloquent
{
    protected $table = 'settings';
	protected $fillable = ['key', 'value', 'timestamps'];
}
