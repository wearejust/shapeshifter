<?php

if ( ! function_exists('__'))
{
	function __($string)
	{
		$string = 'shapeshifter::site.' . $string;

		if (Lang::has( $string ))
		{
		    return Lang::get($string);
		}

		return $string;
	}
}

if ( ! function_exists('translateAttribute'))
{
	function translateAttribute($string)
	{
		$new = 'shapeshifter::attributes.' . $string;

		if (Lang::has( $new ))
		{
		    return ucfirst( Lang::get($new) );
		}

		return ucfirst( $string );
	}
}

if ( ! function_exists('last_block_value'))
{
	function last_block_value($string)
	{
		$string = preg_match_all('/\[+(.*?)\]/', $string, $matches);
		if(isset($matches) && count($matches) > 0)
		{
			$last_item = array_first($matches, function($val){
				return $val;
			});

			return $last_item[1];
		}
		return $string;
	}
}
