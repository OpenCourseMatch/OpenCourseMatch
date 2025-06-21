<?php

namespace validation;

class IsString extends GenericValidator implements ValidatorInterface {
    public function __construct() {}

    public static function create(): ValidatorInterface {
        return new self();
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(!is_string($input)) {
            parent::failValidation();
        }

        return $input;
    }
}
