<?php

namespace validation;

class SelectionValidator extends GenericValidator implements ValidatorInterface {
    private bool $required;
    private array $options;

    private function __construct() {}

    public static function create(
        bool $required = false,
        array $options = []
    ): self {
        $validator = new self();
        $validator->required = $required;
        $validator->options = $options;
        return $validator;
    }

    public function getValidatedValue(mixed &$input): mixed {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            if($this->required) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        } else {
            if(!in_array($input, $this->options, true)) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        }

        return $input ?? null;
    }
}
