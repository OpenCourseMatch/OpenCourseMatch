<?php

namespace app\groups;

class GroupDAO extends \struktal\ORM\GenericEntityDAO {
    public function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return $this->getObject(["id" => $numericId]) instanceof Group;
    }
}
