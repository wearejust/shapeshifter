<?php namespace Just\Shapeshifter\Core\Models;

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
}
