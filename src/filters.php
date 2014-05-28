<?php

/**
 * Login filter
 *
 */
Route::filter('admin-auth', function()
{
    if ( ! Sentry::check())
    {
        return Redirect::route('admin-login');
    }
});

Route::filter('ajax', function($route, $request = null)
{
    if ( ! $request->ajax() ) {
        return App::abort(404);
    }
});
