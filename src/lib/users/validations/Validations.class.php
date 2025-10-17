<?php

namespace app\users;

class Validations {
    public static string $USERNAME_REGEX = "(?!.*\.\.)(?!.*\.$)[^\W][\w.]{2,15}";
    public static string $PASSWORD_REGEX = "(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}";

    public static function sanitizeUsername(string $username): string {
        if(!preg_match("/^" . self::$USERNAME_REGEX . "$/", $username)) {
            throw new InvalidUsernameException();
        }

        return strtolower($username);
    }

    public static function sanitizeEmail(string $email): string {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }

        return strtolower($email);
    }

    public static function checkTwoPasswords(string $password, string $passwordRepeat): void {
        if($password !== $passwordRepeat) {
            throw new PasswordMismatchException();
        }

        self::checkPassword($password);
    }

    public static function checkPassword(string $password): void {
        if(!preg_match("/^" . self::$PASSWORD_REGEX . "$/", $password)) {
            throw new WeakPasswordException();
        }
    }

    public static function username(): \struktal\validation\internals\Validator {
        return Validation->create()
            ->string()
            ->minLength(5)
            ->maxLength(256)
            ->build();
    }

    public static function password(): \struktal\validation\internals\Validator {
        return Validation->create()
            ->string()
            ->minLength(8)
            ->maxLength(256)
            ->build();
    }

    public static function email(?string $errorMessage = null): \struktal\validation\internals\Validator {
        $validation = Validation->create()
            ->string()
            ->email();
        if($errorMessage !== null) {
            $validation->withErrorMessage($errorMessage);
        }
        return $validation->build();
    }

    public static function otp(): \struktal\validation\internals\Validator {
        return Validation->create()
            ->string()
            ->minLength(1)
            ->build();
    }

    public static function urlOtpId(): \struktal\validation\internals\Validator {
        return Validation->create()
            ->string()
            ->minLength(1)
            ->build();
    }

    public static function sessionOtpId(): \struktal\validation\internals\Validator {
        return Validation->create()
            ->int()
            ->build();
    }
}
