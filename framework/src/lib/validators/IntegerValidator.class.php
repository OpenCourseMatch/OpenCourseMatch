<?php

namespace validation;

class IntegerValidator implements InputValidator {
    private bool $required;
    private ?int $minValue;
    private ?int $maxValue;

    public function __construct(
        bool $required = false,
        ?int $minValue = null,
        ?int $maxValue = null
    ) {
        $this->required = $required;
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
    }

    public function validate(mixed &$input): bool {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            return !$this->required;
        } else {
            if(!is_numeric($input)) {
                return false;
            }

            $intval = intval($input);

            if(isset($this->minValue) && $intval < $this->minValue) {
                return false;
            }

            if(isset($this->maxValue) && $intval > $this->maxValue) {
                return false;
            }

            return true;
        }
    }

    public function getValidatedValue(mixed &$input): ?int {
        if($this->validate($input)) {
            return intval($input) ?? null;
        }

        throw new ValidationException("Invalid input");
    }
}
