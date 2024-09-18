<?php

namespace validation;

class MinValue extends GenericValidator implements ValidatorInterface {
    private int $minValue;

    public function __construct(int $minValue) {
        $this->minValue = $minValue;
    }

    public static function create(int $minLength = null): ValidatorInterface {
        return new self($minLength);
    }

    public function getValidatedValue(mixed &$input): mixed {
        if($input < $this->minValue) {
            parent::failValidation();
        }

        return $input;
    }
}
