<?php

namespace app\groups;

class Group extends \struktal\ORM\GenericEntity {
    public ?string $name = null;
    public ?int $clearance = null;

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getClearance(): ?int {
        return $this->clearance;
    }

    public function setClearance(?int $clearance): void {
        $this->clearance = $clearance;
    }

    public function preDelete(): void {
        // Remove all users from the group
        $users = \app\users\User::dao()->getObjects(["groupId" => $this->getId()]);
        foreach($users as $user) {
            $user->setGroupId(null);
            \app\users\User::dao()->save($user);
        }
    }
}
