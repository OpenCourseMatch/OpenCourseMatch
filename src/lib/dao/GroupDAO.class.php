<?php

class GroupDAO extends \struktal\ORM\GenericObjectDAO {
    public function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return $this->getObject(["id" => $numericId]) instanceof Group;
    }
}
