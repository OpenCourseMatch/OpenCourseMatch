<?php

class UserDAO extends GenericUserDAO {
    public function generateUsername(string $firstName, string $lastName): string {
        $slugify = function($input) {
            $slugified = strtolower(str_replace(["ä", "ö", "ü", "ß"], ["ae", "oe", "ue", "ss"], $input));
            return preg_replace("/[^a-zA-Z0-9]/", "", $slugified);
        };

        $firstName = $slugify($firstName);
        $lastName = $slugify($lastName);

        $userSlug = $lastName;
        if(strlen($firstName) > 0) {
            $userSlug .= $firstName[0] . $firstName[strlen($firstName) - 1];
        }

        do {
            $randomNumber = rand(0, 999);
            $appendix = str_pad($randomNumber, 3, "0", STR_PAD_LEFT);
            $appendedUsername = $userSlug . "-" . $appendix;
        } while(count($this->getObjects(["username" => $appendedUsername])) > 0);

        return $appendedUsername;
    }

    public function generatePassword(): string {
        $chars = "123456789abcdefhijkmnoprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ";
        $password = "";
        for($i = 0; $i < 8; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $password;
    }

    public function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return $this->getObject(["id" => $numericId]) instanceof User;
    }

    public function getUnassignedUsers(): array {
        $sql = "SELECT * FROM `User` WHERE `id` NOT IN (SELECT `userId` FROM `Allocation`) AND `permissionLevel` = :permissionLevel";
        $stmt = Database::getConnection()->prepare($sql);
        $stmt->bindValue("permissionLevel", PermissionLevel::USER->value);
        $stmt->execute();

        $objects = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $object = new User();
            $object->fromArray($row);
            $objects[] = $object;
        }

        return $objects;
    }
}
