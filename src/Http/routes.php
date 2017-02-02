<?php


use Dingo\Api\Routing\Router;

$api = app(Router::class);

$api->version('v1', function (Router $api) {

    $api->group(['prefix' => 'admin'], function (Router $api) {
        $api->get('modules', 'Just\Shapeshifter\Http\Controllers\ModuleController@index');
        $api->get('modules/{name}', 'Just\Shapeshifter\Http\Controllers\ModuleController@show');

        $api->resource('news', 'Just\Shapeshifter\Http\Controllers\TestController');
    });

});
