<?php

namespace app\users;

use \app\groups\Group;
use \app\courses\Course;

class UserService {
    public static function login(string $username, bool $loginWithEmail, string $password): User {
        $user = Auth->checkLoginCredentials($username, $loginWithEmail, $password);

        if($user instanceof \struktal\Auth\LoginError) {
            if($user === \struktal\Auth\LoginError::USER_NOT_FOUND) {
                Logger->tag("Login")->info("User \"{$username}\" failed to log in: User not found");
                throw new UserNotFoundException();
            } else if($user === \struktal\Auth\LoginError::INVALID_PASSWORD) {
                Logger->tag("Login")->info("User \"{$username}\" failed to log in: Password incorrect");
                throw new InvalidPasswordException();
            } else if($user === \struktal\Auth\LoginError::EMAIL_NOT_VERIFIED) {
                Logger->tag("Login")->info("User \"{$username}\" failed to log in: Email not verified");
                throw new EmailNotVerifiedException();
            }
        }

        // Reset possibly existing one-time password
        $user->setOneTimePassword(null);
        $user->setOneTimePasswordExpiration(null);
        User::dao()->save($user);

        return $user;
    }

    public static function userExists(?string $username, ?string $email): bool {
        $filters = [];
        if($username !== null) {
            $filters["username"] = $username;
        }
        if($email !== null) {
            $filters["email"] = $email;
        }

        $existingUsers = User::dao()->getObjects($filters);
        return count($existingUsers) > 0;
    }

    public static function register(
        string               $username,
        string               $password,
        PermissionLevel      $permissionLevel,
        string               $firstName,
        string               $lastName,
        ?Group              $group,
        ?Course $leadingCourse
    ): User {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($username);
        $user->setEmailVerified(true);
        $user->setPermissionLevel($permissionLevel);
        $user->setOneTimePassword(null);
        $user->setOneTimePasswordExpiration(null);

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setGroupId($group?->getId());
        $user->setLeadingCourseId($leadingCourse?->getId());
        $user->setLastLogin(null);

        User::dao()->save($user);

        Logger->tag("Register")->info("New user has been registered (\"{$username}\")");

        return $user;
    }

    public static function changePassword(User $user, string $newPassword): void {
        $user->setPassword($newPassword);
        User::dao()->save($user);

        Logger->tag("ChangePassword")->info("User \"{$user->getUsername()}\" has changed their password");
    }

    public static function generateOneTimePassword(): string {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $oneTimePassword = "";
        for($i = 0; $i < 127; $i++) {
            $oneTimePassword .= $chars[rand(0, strlen($chars) - 1)];
        }

        // Check whether the generated one-time-password already exists
        if(count(User::dao()->getObjects([ "oneTimePassword" => $oneTimePassword ])) > 0) {
            $oneTimePassword = self::generateOneTimePassword();
        }

        return $oneTimePassword;
    }
}
