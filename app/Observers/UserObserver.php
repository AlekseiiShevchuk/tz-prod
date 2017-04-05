<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function __construct()
    {

    }

    /**
     * Listen to the User created event.
     *
     * @param  User $user
     * @return void
     */
    public function created(User $user)
    {
        //if password is empty it means that user register with google of facebook
        if (empty($user->password)){
            $user->is_email_valid = 1;
            $user->save();
        }
    }

    public function creating(User $user)
    {
    }

    /**
     * Listen to the User deleting event.
     *
     * @param  User $user
     * @return void
     */
    public function deleting(User $user)
    {
    }
}