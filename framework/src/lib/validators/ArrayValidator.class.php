<?php

namespace validation;

class ArrayValidator implements InputValidator {
    private bool $required;
    private ?InputValidator $itemsValidator;
    private ?int $minLength;
    private ?int $maxLength;

    public function __construct(
        bool $required = false,
        InputValidator $itemsValidator = null,
        ?int $minLength = null,
        ?int $maxLength = null
    ) {
        $this->required = $required;
        $this->itemsValidator = $itemsValidator;
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function validate(mixed &$input): bool {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            return !$this->required;
        } else {
            if(!is_array($input)) {
                return false;
            }

            if(isset($this->itemsValidator)) {
                foreach($input as $item) {
                    if(!$this->itemsValidator->validate($item)) {
                        return false;
                    }
                }
            }

            if(isset($this->minLength) && count($input) < $this->minLength) {
                return false;
            }

            if(isset($this->maxLength) && count($input) > $this->maxLength) {
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
