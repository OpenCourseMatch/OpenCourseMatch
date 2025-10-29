<?php

namespace app\groups;

class GroupService {
    public static function getUsersInGroup(Group $group): array {
        return \app\users\User::dao()->getObjects([
            "groupId" => $group->getId()
        ]);
    }

    public static function delete(Group $group): void {
        // Remove all users from the group
        $users = self::getUsersInGroup($group);
        foreach($users as $user) {
            if(!$user instanceof \app\users\User) {
                continue;
            }

            $user->setGroupId(null);
            \app\users\User::dao()->save($user);
        }
    }
}
