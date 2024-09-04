<?php

namespace validation;

class DAOValidator implements ValidatorInterface {
    private bool $required;
    private \GenericObjectDAO $objectDAO;

    public function __construct(
        bool $required = false,
        ?\GenericObjectDAO $objectDAO = null
    ) {
        $this->required = $required;
        $this->objectDAO = \GenericObject::dao();
        if($objectDAO !== null) {
            $this->objectDAO = $objectDAO;
        }
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
        if($this->validate($input)) {
            return $this->objectDAO->getObject(["id" => $input]);
        }

        throw new ValidationException("Invalid input");
    }
}
