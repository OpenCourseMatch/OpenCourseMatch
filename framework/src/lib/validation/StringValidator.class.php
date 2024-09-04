<?php

namespace validation;

class StringValidator extends GenericValidator implements ValidatorInterface {
    private bool $required;
    private ?int $minLength;
    private ?int $maxLength;

    private function __construct() {}

    public static function create(
        bool $required = false,
        ?int $minLength = null,
        ?int $maxLength = null
    ): self {
        $validator = new self();
        $validator->required = $required;
        $validator->minLength = $minLength;
        $validator->maxLength = $maxLength;
        return $validator;
    }

    public function getValidatedValue(mixed &$input): ?string {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            if($this->required) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        } else {
            if(!is_string($input)) {
                throw new ValidationException([], parent::getErrorMessage());
            }

            if(isset($this->minLength) && strlen($input) < $this->minLength) {
                throw new ValidationException([], parent::getErrorMessage());
            }

            if(isset($this->maxLength) && strlen($input) > $this->maxLength) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        }

        return $input ?? null;
    }
}
