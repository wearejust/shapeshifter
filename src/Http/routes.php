<?php

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');

use Dingo\Api\Routing\Router;

$api = app(Router::class);

$api->version('v1', function (Router $api) {

    $api->group(['prefix' => 'api'], function (Router $api) {
        $api->get('modules', 'Just\Shapeshifter\Http\Controllers\ModuleController@index');
        $api->get('modules/{name}', 'Just\Shapeshifter\Http\Controllers\ModuleController@show');

        $api->resource('news', 'Just\Shapeshifter\Http\Controllers\TestController');
        $api->resource('blog', 'Just\Shapeshifter\Http\Controllers\Test2Controller');
    });

});
