<?php namespace Just\Shapeshifter\Core\Models;

class Language extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'languages';
	public $fillable = array('short_code', 'active', 'name', 'default');
}
