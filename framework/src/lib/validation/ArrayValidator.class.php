<?php

namespace validation;

class ArrayValidator extends GenericValidator implements ValidatorInterface {
    private bool $required;
    private ?ValidatorInterface $itemsValidator;
    private ?int $minLength;
    private ?int $maxLength;

    private function __construct() {}

    public static function create(
        bool $required = false,
        ValidatorInterface $itemsValidator = null,
        ?int $minLength = null,
        ?int $maxLength = null
    ): self {
        $validator = new self();
        $validator->required = $required;
        $validator->itemsValidator = $itemsValidator;
        $validator->minLength = $minLength;
        $validator->maxLength = $maxLength;
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

            if(isset($this->itemsValidator)) {
                foreach($input as $item) {
                    try {
                        $this->itemsValidator->getValidatedValue($item);
                    } catch(ValidationException $e) {
                        throw new ValidationException([], $e->getMessage());
                    }
                }
            }

            if(isset($this->minLength) && count($input) < $this->minLength) {
                throw new Validationexception([], parent::getErrorMessage());
            }

            if(isset($this->maxLength) && count($input) > $this->maxLength) {
                throw new Validationexception([], parent::getErrorMessage());
            }
        }

        return $input ?? null;
    }
}
