<?php

namespace validation;

class MaxValue extends GenericValidator implements ValidatorInterface {
    private int $maxValue;

    public function __construct(int $maxValue) {
        $this->maxValue = $maxValue;
    }

    public static function create(int $minLength = null): ValidatorInterface {
        return new self($minLength);
    }

    public function getValidatedValue(mixed &$input): mixed {
        if($input > $this->maxValue) {
            parent::failValidation();
        }

        return $input;
    }
}
