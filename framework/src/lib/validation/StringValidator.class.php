<?php

namespace validation;

class StringValidator implements ValidatorInterface {
    private bool $required;
    private ?int $minLength;
    private ?int $maxLength;

    public function __construct(
        bool $required = false,
        ?int $minLength = null,
        ?int $maxLength = null
    ) {
        $this->required = $required;
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function validate(mixed &$input): bool {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            return !$this->required;
        } else {
            if(!is_string($input)) {
                return false;
            }

            if(isset($this->minLength) && strlen($input) < $this->minLength) {
                return false;
            }

            if(isset($this->maxLength) && strlen($input) > $this->maxLength) {
                return false;
            }

            return true;
        }
    }

    public function getValidatedValue(mixed &$input): ?string {
        if($this->validate($input)) {
            return $input ?? null;
        }

        throw new ValidationException("Invalid input");
    }
}
