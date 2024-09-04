<?php

namespace validation;

class IntegerValidator extends GenericValidator implements ValidatorInterface {
    private bool $required;
    private ?int $minValue;
    private ?int $maxValue;

    private function __construct() {}

    public static function create(
        bool $required = false,
        ?int $minValue = null,
        ?int $maxValue = null
    ): self {
        $validator = new self();
        $validator->required = $required;
        $validator->minValue = $minValue;
        $validator->maxValue = $maxValue;
        return $validator;
    }

    public function getValidatedValue(mixed &$input): ?int {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            if($this->required) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        } else {
            if(!is_numeric($input)) {
                throw new ValidationException([], parent::getErrorMessage());
            }

            $intval = intval($input);

            if(isset($this->minValue) && $intval < $this->minValue) {
                throw new ValidationException([], parent::getErrorMessage());
            }

            if(isset($this->maxValue) && $intval > $this->maxValue) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        }

        return intval($input) ?? null;
    }
}
