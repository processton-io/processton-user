<?php
namespace App\Resolvers\User;
use App\Events\UserInvited;

class UserInvitationResolver
{
    public static function handle($user)
    {
        event(new UserInvited($user));
    }
}