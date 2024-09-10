<?php

namespace validation;

class HasChildren extends GenericValidator implements ValidatorInterface {
    private array $children;
    private bool $allowAdditionalFields;

    public function __construct(array $children = [], bool $allowAdditionalFields = false) {
        $this->children = $children;
        $this->allowAdditionalFields = $allowAdditionalFields;
    }

    public static function create(array $children = [], bool $allowAdditionalFields = false): ValidatorInterface {
        return new self($children, $allowAdditionalFields);
    }

    public function getValidatedValue(mixed &$input): mixed {
        $output = [];

        /**
         * @var string $key
         * @var ValidatorInterface $validator
         */
        foreach($this->children as $key => $validator) {
            $output[$key] = $validator->getValidatedValue($input[$key]);
        }

        if($this->allowAdditionalFields) {
            foreach($input as $key => $value) {
                if(array_key_exists($key, $this->children)) {
                    continue;
                }

                $output[$key] = $value;
            }
        }

        return $output;
    }
}
