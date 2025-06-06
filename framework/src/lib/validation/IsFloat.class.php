<?php

namespace validation;

class IsFloat extends GenericValidator implements ValidatorInterface {
    public function __construct() {}

    public static function create(): ValidatorInterface {
        return new self();
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(!is_float($input)) {
            parent::failValidation();
        }

        return floatval($input);
    }
}
