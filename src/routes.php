<?php

use Just\Shapeshifter\Core\Composer\Layout;
use Just\Shapeshifter\Core\Controllers\AjaxController;

/*
|--------------------------------------------------------------------------
| View composers
|--------------------------------------------------------------------------
*/

View::composer(['shapeshifter::*'], Layout::class);

/*
|--------------------------------------------------------------------------
| Core Routes
|--------------------------------------------------------------------------
|
| These are the routes that are neccesary for ShapeShifter to run. Be default
| ShapeShifter will be available at /admin. Also notice that the routes
| `users` and `groups` are listed here.
|
*/

Route::group(['prefix' => 'admin'], function () {

    Route::get('alive-check', function(){
        return [
            'is_alive' => (bool) \Sentinel::check()
        ];
    });

    Route::group(['before' => 'admin-auth'], function () {
        Route::post('ajax/sortorderchange', getAction(AjaxController::class, 'sortorderChange'))->before('ajax');
        Route::post('ajax/upload', getAction(AjaxController::class, 'upload'))->before('ajax');
    });
});
