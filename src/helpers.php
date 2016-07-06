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

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffix = ['', 'K', 'M', 'G', 'T'][floor($base)];
    $size = round(pow(1024, $base - floor($base)), $precision);
    if ($size >= 100) $size = round($size);
    return $size . $suffix . 'B';
}
