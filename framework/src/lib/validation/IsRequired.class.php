<?php

namespace validation;

class IsRequired extends GenericValidator implements ValidatorInterface {
    private bool $checkWithEmpty;

    public function __construct(bool $checkWithEmpty = false) {
        $this->checkWithEmpty = $checkWithEmpty;
    }

    public static function create(bool $checkWithEmpty = false): ValidatorInterface {
        return new self($checkWithEmpty);
    }

    public function getValidatedValue(mixed &$input): mixed {
        if($this->checkWithEmpty) {
            if(empty($input)) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        } else {
            if(!isset($input)) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        }

        return $input;
    }
}
