<?php

namespace validation;

class MinLength extends GenericValidator implements ValidatorInterface {
    private int $minLength;

    public function __construct(int $minLength) {
        $this->minLength = $minLength;
    }

    public static function create(int $minLength = null): ValidatorInterface {
        return new self($minLength);
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(is_string($input) && strlen($input) < $this->minLength) {
            throw new ValidationException([], parent::getErrorMessage());
        } else if(is_array($input) && count($input) < $this->minLength) {
            throw new ValidationException([], parent::getErrorMessage());
        }

        return $input;
    }
}
