<?php

namespace validation;

class HasProperties extends GenericValidator implements ValidatorInterface {
    private array $properties;

    public function __construct(array $properties = []) {
        $this->properties = $properties;
    }

    public static function create(array $children = [], bool $allowAdditionalFields = false): ValidatorInterface {
        return new self($children);
    }

    public function getValidatedValue(mixed &$input): mixed {
        /**
         * @var string $key
         * @var ValidatorInterface $validator
         */
        foreach($this->properties as $key => $validator) {
            try {
                $validator->getValidatedValue($input->$key);
            } catch(ValidationException $e) {
                if($e->getMessage() !== PHP_EOL) {
                    throw $e;
                } else {
                    parent::failValidation();
                }
            }
        }

        return $input;
    }
}
