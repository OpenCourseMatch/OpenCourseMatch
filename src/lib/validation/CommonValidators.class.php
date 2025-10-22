<?php

use struktal\validation\internals\Validator;

class CommonValidators {
    public static function name(): Validator {
        return Validation->create()
            ->string()
            ->minLength(1)
            ->maxLength(64)
            ->build();
    }

    public static function username(): Validator {
        return Validation->create()
            ->string()
            ->minLength(5)
            ->maxLength(256)
            ->build();
    }

    public static function password(bool $required = true): Validator {
        return Validation->create()
            ->string($required)
            ->minLength(8)
            ->maxLength(256)
            ->build();
    }

    public static function user(bool $required = true, array $additionalFilters = [], ?string $errorMessage = null): Validator {
        $validation = Validation->create();
        if($errorMessage !== null) {
            $validation->withErrorMessage($errorMessage);
        }
        $validation->inDatabase(\app\users\User::dao(), $additionalFilters);
        if($required) {
            $validation->required();
        }

        return $validation->build();
    }

    public static function course(bool $required = true, array $additionalFilters = [], ?string $errorMessage = null): Validator {
        $validation = Validation->create();
        if($errorMessage !== null) {
            $validation->withErrorMessage($errorMessage);
        }
        $validation->inDatabase(\app\courses\Course::dao(), $additionalFilters);
        if($required) {
            $validation->required();
        }

        return $validation->build();
    }

    public static function group(bool $required = true, array $additionalFilters = [], ?string $errorMessage = null): Validator {
        $validation = Validation->create();
        if($errorMessage !== null) {
            $validation->withErrorMessage($errorMessage);
        }
        $validation->inDatabase(\app\groups\Group::dao(), $additionalFilters);
        if($required) {
            $validation->required();
        }

        return $validation->build();
    }

    public static function checkbox(): Validator {
        return Validation->create()
            ->int(false)
            ->build();
    }
}
