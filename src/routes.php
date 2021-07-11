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

Route::group(['as' => 'admin.', 'prefix' => 'admin'], function () {

    Route::group(['before' => 'admin-auth'], function () {
        Route::post('ajax/sortorderchange', getAction(AjaxController::class, 'sortorderChange'));
        Route::post('ajax/upload', getAction(AjaxController::class, 'upload'));
    });
});
