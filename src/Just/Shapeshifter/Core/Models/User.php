<?php namespace Just\Shapeshifter\Core\Models;

use App;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;

class User extends SentryUser
{
    protected $table = 'cms_users';
    protected $disabledActions = array();

    public function can($action)
    {
        return ! in_array($action, $this->disabledActions);
    }

    public function setDisabledActions($disabledActions)
    {
        $this->disabledActions = $disabledActions;
    }


    public function getPersistCode()
    {
        if (App::environment() !== 'local')
        {
            return parent::getPersistCode();
        }

        if ( ! $this->persist_code)
        {
            $this->persist_code = $this->getRandomString();

            // Our code got hashed
            $persistCode = $this->persist_code;

            $this->save();

            return $persistCode;
        }

        return $this->persist_code;
    }
}
