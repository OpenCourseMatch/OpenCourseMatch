<?php

namespace validation;

class FloatValidator implements ValidatorInterface {
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
            if(!is_float($input)) {
                return false;
            }

            $floatval = floatval($input);

            if(isset($this->minValue) && $floatval < $this->minValue) {
                return false;
            }

            if(isset($this->maxValue) && $floatval > $this->maxValue) {
                return false;
            }

            return true;
        }
    }

    public function getValidatedValue(mixed &$input): ?float {
        if($this->validate($input)) {
            return floatval($input) ?? null;
        }

        throw new ValidationException("Invalid input");
    }
}
