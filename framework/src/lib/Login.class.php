<?php

class Login {
    /**
     * Enforces the user to be logged in
     * @param mixed $userId User ID
     * @param int $requiredPermissionLevel Minimum required permission level
     * @param string $redirect Redirect URL if the user is not logged in
     * @return GenericUser|null User object or null if the user is not logged in
     */
    public static function requireLogin(mixed $userId, int $requiredPermissionLevel, string $redirect): ?GenericUser {
        if(empty($userId) || !(is_int($userId))) {
            Comm::redirect($redirect);
            return null;
        }

        $user = User::dao()->getObject(["id" => $userId, "emailVerified" => true]);

        if($user instanceof User) {
            if($user->getPermissionLevel() >= $requiredPermissionLevel) {
                return $user;
            }
        }

        Comm::redirect($redirect);
        return null;
    }

    /**
     * Checks whether the page was called by a logged in user
     * @param mixed $userId User ID
     * @return GenericUser|null User object or null if no user is logged in
     */
    public static function optionalLogin(mixed $userId): ?GenericUser {
        if(empty($userId) || !(is_int($userId))) {
            return null;
        }

        $user = User::dao()->getObject(["id" => $userId, "emailVerified" => true]);

        if($user instanceof User) {
            return $user;
        }

        return null;
    }
}
