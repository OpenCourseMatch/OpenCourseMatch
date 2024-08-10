<?php

class Auth {
    /**
     * Checks whether the user is logged in
     * @return bool
     */
    public static function isLoggedIn(): bool {
        return !empty($_SESSION["userId"]);
    }

    /**
     * Gets the logged-in user, or null if no user is logged in
     * @return User|null
     */
    public static function getLoggedInUser(): ?User {
        if(!self::isLoggedIn()) {
            return null;
        }

        $user = User::dao()->getObject(["id" => $_SESSION["userId"], "emailVerified" => true]);
        if($user instanceof User) {
            return $user;
        }

        self::logout();
        return null;
    }

    /**
     * Enforces the user to be logged in with a minimum permission level, and redirects the user if they do not meet the requirements
     * @param int    $requiredPermissionLevel
     * @param string $redirect
     * @return User|null
     */
    public static function enforceLogin(int $requiredPermissionLevel, string $redirect): ?User {
        $user = self::getLoggedInUser();
        if(!$user instanceof User) {
            Comm::redirect($redirect);
            return null;
        }

        if($user->getPermissionLevel() < $requiredPermissionLevel) {
            Comm::redirect($redirect);
            return null;
        }

        return $user;
    }

    /**
     * Sets the session entry for the user to be logged in
     * @param User $user
     * @return void
     */
    public static function login(User $user): void {
        $_SESSION["userId"] = $user->getId();
    }

    /**
     * Logs the user out
     * @return void
     */
    public static function logout(): void {
        unset($_SESSION["userId"]);
    }
}
