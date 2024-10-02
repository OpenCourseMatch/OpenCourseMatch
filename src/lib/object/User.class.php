<?php

class User extends GenericUser {
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?int $group = null;
    public ?int $leadingCourse = null;
    public ?string $lastLogin = null;

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

    public function getGroup(): ?int {
        return $this->group;
    }

    public function setGroup(?int $group): void {
        $this->group = $group;
    }

    public function getLeadingCourse(): ?int {
        return $this->leadingCourse;
    }

    public function setLeadingCourse(?int $leadingCourse): void {
        $this->leadingCourse = $leadingCourse;
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
}
