<?php

namespace validation;

class IsArray extends GenericValidator implements ValidatorInterface {
    public function __construct() {}

    public static function create(): ValidatorInterface {
        return new self();
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(!is_array($input)) {
            throw new ValidationException([], parent::getErrorMessage());
        }

        return $input;
    }
}