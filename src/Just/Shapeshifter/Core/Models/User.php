<?php namespace Just\Shapeshifter\Core\Models;

use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;

class User extends SentryUser
{
    protected $table = 'cms_users';
}
