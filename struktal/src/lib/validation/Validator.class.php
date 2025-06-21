<?php

namespace validation;

class Validator extends GenericValidator implements ValidatorInterface {
    private array $validators;

    /** @var $validators ValidatorInterface[] */
    public function __construct(array $validators = []) {
        $this->validators = $validators;
    }

    public static function create(array $validators = []) {
        return new self($validators);
    }

    public function getValidatedValue(mixed &$input): mixed {
        $previousValue = $input;

        $required = false;
        $thrownException = null;

        try {
            foreach($this->validators as $validator) {
                if($validator instanceof IsRequired) {
                    $required = true;
                }

                $newValue = $validator->getValidatedValue($previousValue);
                $previousValue = $newValue;
            }
        } catch(ValidationException $e) {
            if($required) {
                $thrownException = $e;
            } else {
                $requiredCheck = new IsRequired();
                try {
                    $requiredCheck->getValidatedValue($previousValue);

                    // If the exception isn't thrown, the input was set, but is of a bad type
                    $thrownException = $e;
                } catch(ValidationException $newE) {
                    // If the exception does throw, the input isn't set, thus optional
                    return null;
                }
            }
        }

        if($thrownException != null) {
            if($thrownException->getMessage() != PHP_EOL) {
                throw $thrownException;
            } else {
                parent::failValidation();
            }
        }

        return $previousValue;
    }
}
