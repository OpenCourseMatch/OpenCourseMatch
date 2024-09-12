<?php

namespace validation;

class NullOnEmpty extends GenericValidator implements ValidatorInterface {
    public function __construct() {}

    public static function create(): ValidatorInterface {
        return new self();
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(empty($input)) {
            return null;
        }

        return $input;
    }
}
