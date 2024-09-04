<?php

namespace validation;

class ObjectValidator extends GenericValidator implements ValidatorInterface {
    private bool $required;
    /** @var ValidatorInterface[]|null */
    private ?array $fieldsValidator;
    private ?bool $allowUnspecifiedFields;

    private function __construct() {}

    public static function create(
        bool $required = false,
        array $fieldsValidator = null,
        ?bool $allowUnspecifiedFields = null,
    ): self {
        $validator = new self();
        $validator->required = $required;
        $validator->fieldsValidator = $fieldsValidator;
        $validator->allowUnspecifiedFields = $allowUnspecifiedFields;
        return $validator;
    }

    public function getValidatedValue(mixed &$input): ?array {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            if($this->required) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        } else {
            if(!is_array($input)) {
                throw new ValidationException([], parent::getErrorMessage());
            }

            if(isset($this->fieldsValidator)) {
                foreach($input as $key => $item) {
                    // If no additional fields are allowed, fail if there is no key in the fields validator array
                    if(!isset($this->fieldsValidator[$key]) && $this->allowUnspecifiedFields) {
                        throw new ValidationException([], parent::getErrorMessage());
                    }

                    // Check whether the field is valid
                    try {
                        $this->fieldsValidator[$key]->getValidatedValue($item);
                    } catch(ValidationException $e) {
                        throw new ValidationException([$key], $e->getMessage());
                    }

                    // TODO: Check whether all required fields were set
                }
            }
        }

        return $input ?? null;
    }
}
