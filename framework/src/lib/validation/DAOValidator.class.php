<?php

namespace validation;

class DAOValidator extends GenericValidator implements ValidatorInterface {
    private bool $required;
    private \GenericObjectDAO $objectDAO;

    private function __construct() {}

    public static function create(
        bool $required = false,
        ?\GenericObjectDAO $objectDAO = null
    ): self {
        $validator = new self();
        $validator->required = $required;
        $validator->objectDAO = $objectDAO;
        return $validator;
    }

    public function validate(mixed &$input): bool {
        // If the input is not set, the validation fails if the input is required
        // Otherwise, check whether all constraints are satisfied
        if(!isset($input)) {
            return !$this->required;
        } else {
            if(!is_numeric($input)) {
                return false;
            }

            $intval = intval($input);

            $object = $this->objectDAO->getObject(["id" => $intval]);
            if(!$object instanceof \GenericObject) {
                return false;
            }

            return true;
        }
    }

    public function getValidatedValue(mixed &$input): ?\GenericObject {
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

            $object = $this->objectDAO->getObject(["id" => $intval]);
            if(!$object instanceof \GenericObject) {
                throw new ValidationException([], parent::getErrorMessage());
            }
        }

        return $object ?? null;
    }
}
