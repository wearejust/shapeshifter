<?php  namespace Just\Shapeshifter\Core\Controllers; 

use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Just\Shapeshifter\Core\Models\User;
use View;
use Input;
use Validator;
use Redirect;
use Notification;
use Sentry;

class AuthController extends \Controller
{
    public function getLogin()
    {
        return View::make('shapeshifter::login', array(
            'model' => new User(),
            'currentUser' => null
        ));
    }

    public function postLogin()
    {
        $credentials = Input::only(array('email','password'));

        try
        {
            Sentry::authenticate($credentials);

            return Redirect::route('admin-home');
        }
        catch (LoginRequiredException $e)
        {
            Notification::error( __('login.failure') );
        }
        catch (PasswordRequiredException $e)
        {
            Notification::error( __('login.failure') );
        }
        catch (WrongPasswordException $e)
        {
            Notification::error( __('login.failure') );
        }
        catch (UserNotFoundException $e)
        {
            Notification::error( __('login.failure') );
        }
        catch (UserNotActivatedException $e)
        {
            Notification::error( __('login.failure') );
        }
        catch (UserSuspendedException $e)
        {
            Notification::error( __('login.suspended') );
        }
        catch (UserBannedException $e)
        {
            Notification::error( __('login.failure') );
        }

        return Redirect::route('admin-login')->withInput();
    }

    public function getLogout()
    {
        Sentry::logout();

        return Redirect::route('admin-login');
    }
} 
