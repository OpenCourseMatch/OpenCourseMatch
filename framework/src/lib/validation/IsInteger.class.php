<?php

namespace validation;

class IsInteger extends GenericValidator implements ValidatorInterface {
    public function __construct() {}

    public static function create(): ValidatorInterface {
        return new self();
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(!is_numeric($input)) {
            parent::failValidation();
        }

        return intval($input);
    }
}
