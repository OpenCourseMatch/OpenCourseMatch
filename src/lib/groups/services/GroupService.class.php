<?php

namespace app\groups;

use \app\users\User;

class GroupService {
    public static function delete(Group $group): void {
        // Remove all users from the group
        $users = self::getUsersInGroup($group);
        foreach($users as $user) {
            $user->setGroupId(null);
            User::dao()->save($user);
        }

        Group::dao()->delete($group);
    }

    public static function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return Group::dao()->getObject(["id" => $numericId]) instanceof Group;
    }

    public static function getUsersInGroup(Group $group): array {
        return User::dao()->getObjects([ "groupId" => $group->getId() ]);
    }
}
