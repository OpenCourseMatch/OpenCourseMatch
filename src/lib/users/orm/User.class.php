<?php

namespace app\users;

class User extends \struktal\ORM\GenericUser {
    #[\struktal\ORM\InheritedType(PermissionLevel::class)]
    public ?\struktal\Auth\PermissionLevel $permissionLevel = null;
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?int $groupId = null;
    public ?int $leadingCourseId = null;
    public ?bool $showHelpBoxes = null;
    public ?\DateTimeImmutable $lastLogin = null;

    private ?\app\groups\Group $group = null;
    private ?\app\courses\Course $leadingCourse = null;
    private ?array $choices = null;
    private ?\app\courses\Course $assignedCourse = null;

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

    public function getShowHelpBoxes(): ?bool {
        return $this->showHelpBoxes;
    }

    public function setShowHelpBoxes(?bool $showHelpBoxes): void {
        $this->showHelpBoxes = $showHelpBoxes;
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

    public function getGroup(): ?\app\groups\Group {
        if(!$this->group) {
            if($this->getGroupId() === null) {
                $this->group = null;
            } else {
                $this->group = \app\groups\Group::dao()->getObject(["id" => $this->getGroupId()]);
            }
        }

        return $this->group;
    }

    public function getLeadingCourse(): ?\app\courses\Course {
        if(!$this->leadingCourse) {
            if($this->getLeadingCourseId() === null) {
                $this->leadingCourse = null;
            } else {
                $this->leadingCourse = \app\courses\Course::dao()->getObject(["id" => $this->getLeadingCourseId()]);
            }
        }

        return $this->leadingCourse;
    }

    public function getSortedChoices(): array {
        if(!$this->choices) {
            $this->choices = \app\choices\ChoiceService::getSortedChoicesForUser($this);
        }

        return $this->choices;
    }

    public function getChoice(int $priority): ?\app\choices\Choice {
        return \app\choices\ChoiceService::getChoiceWithPriorityForUser($this, $priority);
    }

    public function getCoursePriority(\app\courses\Course $course): ?int {
        return \app\choices\ChoiceService::getCoursePriorityForUser($this, $course);
    }

    public function getAssignedCourse(): ?\app\courses\Course {
        if(!$this->assignedCourse) {
            $this->assignedCourse = \app\assignments\AssignmentService::getAssignedCourseForUser($this);
        }

        return $this->assignedCourse;
    }
}
