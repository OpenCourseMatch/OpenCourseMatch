<?php

namespace app\users;

use \struktal\ORM\Database\Database;

class UserDAO extends \struktal\ORM\GenericUserDAO {
    public function getUnassignedUsers(): array {
        $sql = "SELECT * FROM `User` WHERE `id` NOT IN (SELECT `userId` FROM `Assignment`) AND `permissionLevel` = :permissionLevel";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue("permissionLevel", PermissionLevel::USER->value);
        $stmt->execute();

        $objects = [];
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $object = new User();
            $object->fromArray($row);
            $objects[] = $object;
        }

        return $objects;
    }
}
