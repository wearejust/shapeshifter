<?php

if (! function_exists('__')) {
    function __($string)
    {
        $string = 'shapeshifter::site.' . $string;

        if (Lang::has($string)) {
            return Lang::get($string);
        }

        return $string;
    }
}

if (! function_exists('translateAttribute')) {
    function translateAttribute($string)
    {
        $new = 'shapeshifter::attributes.' . $string;

        if (Lang::has($new)) {
            return ucfirst(Lang::get($new));
        }

        return ucfirst($string);
    }
}

if (! function_exists('getAction')) {
    function getAction($class, $method)
    {
        return $class . '@' . $method;
    }
}
