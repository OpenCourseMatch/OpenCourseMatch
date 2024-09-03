<?php

namespace validation;

class ObjectValidator implements ValidatorInterface {
    private bool $required;
    private ?array $fieldsValidator;
    private ?bool $allowUnspecifiedFields;

    public function __construct(
        bool $required = false,
        array $fieldsValidator = null,
        ?bool $allowUnspecifiedFields = null,
    ) {
        $this->required = $required;
        $this->fieldsValidator = $fieldsValidator;
        $this->allowUnspecifiedFields = $fieldsValidator;
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

            if(isset($this->fieldsValidator)) {
                foreach($input as $key => $item) {
                    // If no additional fields are allowed, fail if there is no key in the attributes validator array
                    if(!isset($this->fieldsValidator[$key]) && $this->allowUnspecifiedFields) {
                        return false;
                    }

                    // Check whether the field is valid
                    if(!$this->fieldsValidator[$key]->validate($item)) {
                        return false;
                    }
                }
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
