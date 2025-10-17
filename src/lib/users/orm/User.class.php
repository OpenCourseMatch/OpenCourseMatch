<?php

namespace app\users;

use \app\groups\Group;
use \app\courses\Course;

class User extends \struktal\ORM\GenericUser {
    #[\struktal\ORM\InheritedType(PermissionLevel::class)]
    public ?\struktal\Auth\PermissionLevel $permissionLevel = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?int $groupId = null;
    public ?int $leadingCourseId = null;
    public ?\DateTimeImmutable $lastLogin = null;

    private ?Group $group = null;
    private ?Course $leadingCourse = null;

    public function getFirstName(): ?string {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void {
        $this->lastName = $lastName;
    }

    public function getGroupId(): ?int {
        return $this->groupId;
    }

    public function setGroupId(?int $groupId): void {
        $this->groupId = $groupId;
    }

    public function getLeadingCourseId(): ?int {
        return $this->leadingCourseId;
    }

    public function setLeadingCourseId(?int $leadingCourseId): void {
        $this->leadingCourseId = $leadingCourseId;
    }

    public function getLastLogin(): ?\DateTimeImmutable {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeImmutable $lastLogin): void {
        $this->lastLogin = $lastLogin;
    }

    public function getFullName(): string {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    public function getGroup(): ?Group {
        if(!$this->group) {
            if($this->getGroupId() === null) {
                $this->group = null;
            } else {
                $this->group = Group::dao()->getObject(["id" => $this->getGroupId()]);
            }
        }

        return $this->group;
    }

    public function getLeadingCourse(): ?Course {
        if(!$this->leadingCourse) {
            if($this->getLeadingCourseId() === null) {
                $this->leadingCourse = null;
            } else {
                $this->leadingCourse = Course::dao()->getObject(["id" => $this->getLeadingCourseId()]);
            }
        }

        return $this->leadingCourse;
    }
}
