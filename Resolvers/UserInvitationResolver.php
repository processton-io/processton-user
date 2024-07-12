<?php
namespace App\Resolvers\User;
use Illuminate\Auth\Events\Registered;

class UserInvitationResolver
{
    public static function handle($user)
    {
        event(new Registered($user));
    }
}