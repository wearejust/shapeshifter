<?php namespace Just\Shapeshifter\Facades;

use Illuminate\Support\Facades\Facade;

class SS extends Facade
{

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor () { return 'ss'; }

}
