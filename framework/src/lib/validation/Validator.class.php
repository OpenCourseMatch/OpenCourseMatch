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
        $temp = $input;

        try {
            foreach($this->validators as $validator) {
                $temp = $validator->getValidatedValue($temp);
            }
        } catch(ValidationException $e) {
            if($e->getMessage() === "") {
                throw new ValidationException($e->getErrorFields(), parent::getErrorMessage());
            }
        }

        return $temp;
    }
}
