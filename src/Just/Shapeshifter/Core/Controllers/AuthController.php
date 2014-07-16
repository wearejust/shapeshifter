<?php  namespace Just\Shapeshifter\Core\Controllers; 

use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Illuminate\Foundation\Application;
use Just\Shapeshifter\Core\Models\User;
use Notification;
use Sentry;


class AuthController extends \Controller
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getLogin()
    {
        return $this->app['view']->make('shapeshifter::login', array(
            'model' => new User(),
            'currentUser' => null
        ));
    }

    public function postLogin()
    {
        $credentials = $this->app['request']->only(array('email','password'));

        try
        {
            Sentry::authenticate($credentials);

            return $this->app['redirect']->route('admin-home');
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

        return $this->app['redirect']->route('admin-login')->withInput();
    }

    public function getLogout()
    {
        Sentry::logout();

        return $this->app['redirect']->route('admin-login');
    }
} 
