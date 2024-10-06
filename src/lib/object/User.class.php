<?php

class User extends GenericUser {
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?int $groupId = null;
    public ?int $leadingCourseId = null;
    public ?string $lastLogin = null;

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

    public function getLastLogin(): ?string {
        return $this->lastLogin;
    }

    public function setLastLogin(?string $lastLogin): void {
        $this->lastLogin = $lastLogin;
    }

    public function getFullName(): string {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    public function getGroup(): ?Group {
        if(!$this->group) {
            $this->group = Group::dao()->getObject(["id" => $this->getGroupId()]);
        }

        return $this->group;
    }

    public function getLeadingCourse(): ?Course {
        if(!$this->leadingCourse) {
            $this->leadingCourse = Course::dao()->getObject(["id" => $this->getLeadingCourseId()]);
        }

        return $this->leadingCourse;
    }
}
