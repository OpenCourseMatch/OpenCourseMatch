<?php

namespace validation;

class SelectionValidator implements ValidatorInterface {
    private bool $required;
    private array $options;

    public function __construct(
        bool $required = false,
        array $options = []
    ) {
        $this->required = $required;
        $this->options = $options;
    }

    public function validate(mixed &$input): bool {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            return !$this->required;
        } else {
            if(!isset($input)) {
                return false;
            }

            if(!in_array($input, $this->options, true)) {
                return false;
            }

            return true;
        }
    }

    public function getValidatedValue(mixed &$input): ?array {
        if($this->validate($input)) {
            return $input ?? null;
        }

        throw new ValidationException("Invalid input");
    }
}
