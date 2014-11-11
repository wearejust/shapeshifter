<?php

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

Route::group(array('prefix' => 'admin'), function()
{
	Route::get('login', array('as' => 'admin-login', 'uses' => 'Just\Shapeshifter\Core\Controllers\AuthController@getLogin'));
	Route::post('login', array('uses' => 'Just\Shapeshifter\Core\Controllers\AuthController@postLogin'));

    Route::group(array('before' =>'admin-auth'), function()
    {
        Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showCKeditor4');
        Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');

        Route::get('logout', array('as' => 'admin-logout', 'uses' => 'Just\Shapeshifter\Core\Controllers\AuthController@getLogout'));
        Route::post('ajax/sortorderchange', 'Just\Shapeshifter\Core\Controllers\AjaxController@sortorderChange')->before('ajax');
        Route::post('ajax/upload', 'Just\Shapeshifter\Core\Controllers\AjaxController@upload')->before('ajax');

        Route::resource('users', 'Just\Shapeshifter\Core\Controllers\UserController');
        Route::resource('groups', 'Just\Shapeshifter\Core\Controllers\GroupController');
        Route::resource('settings', 'Just\Shapeshifter\Core\Controllers\SettingsController');
    });
});

Route::group(array('before' =>'admin-auth'), function()
{
    Route::get('preview', function()
    {
        return View::make('shapeshifter::preview');
    });
});
