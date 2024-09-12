<?php

namespace validation;

class IsInDatabase extends GenericValidator implements ValidatorInterface {
    private \GenericObjectDAO $dao;
    private array $additionalFilters;

    public function __construct(\GenericObjectDAO $dao, array $additionalFilters = []) {
        $this->dao = $dao;
        $this->additionalFilters = $additionalFilters;
    }

    public static function create(\GenericObjectDAO $dao = null, array $additionalFilters = []): ValidatorInterface {
        if($dao === null) {
            $dao = \GenericObject::dao();
        }

        return new self($dao, $additionalFilters);
    }

    public function getValidatedValue(mixed &$input): mixed {
        if(!is_numeric($input)) {
            return null;
        }

        $objectId = intval($input);

        $filters = $this->additionalFilters;
        $filters["id"] = $objectId;

        $object = $this->dao->getObject($filters);
        if(!$object instanceof \GenericObject) {
            parent::failValidation();
        }

        return $object;
    }
}
