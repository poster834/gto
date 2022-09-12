<?php
namespace Gtm\Models\Users;

class UsersAuthService
{
    public static function createToken(User $user)
    {
        $token = $user->getId() . ':'.$user->getAuthToken();
        setcookie('token', $token, 0, '/', '', false, true);
    }

    public static function getUserByToken()
    {
        $token = $_COOKIE['token'] ?? '';

        if (empty($token)) {
            return null;
        }

        $userId = explode(':', $token, 2)[0];
        $authToken = explode(':', $token, 2)[1];

        $user = User::getById((int) $userId);

        if ($user === null) {
            return null;
        }

        if ($user->getAuthToken() !== $authToken) {
            return null;
        }

        return $user;
    }

}