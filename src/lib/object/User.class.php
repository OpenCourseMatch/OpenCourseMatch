<?php

class User extends GenericUser {
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?int $groupId = null;
    public ?int $leadingCourseId = null;
    public ?DateTimeImmutable $lastLogin = null;

    private ?Group $group = null;
    private ?Course $leadingCourse = null;
    private ?array $chosenCourses = null;

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

    public function getLastLogin(): ?DateTimeImmutable {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTimeImmutable $lastLogin): void {
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

    public function getChoices(): array {
        if(!$this->chosenCourses) {
            $chosenCourses = Choice::dao()->getObjects(["userId" => $this->getId()], "priority");
            $voteCount = intval(SystemSetting::dao()->get("voteCount"));

            $this->chosenCourses = [];
            for($i = 0; $i < $voteCount; $i++) {
                $this->chosenCourses[$i] = null;
            }

            foreach($chosenCourses as $chosenCourse) {
                $this->chosenCourses[$chosenCourse->getPriority()] = $chosenCourse;
            }
        }

        return $this->chosenCourses;
    }

    public function getChoice(int $priority): ?Choice {
        $chosenCourses = $this->getChoices();
        return $chosenCourses[$priority];
    }

    public function preDelete(): void {
        // Delete all choices
        $choices = $this->getChoices();
        foreach($choices as $choice) {
            if($choice instanceof Choice) {
                Choice::dao()->delete($choice);
            }
        }

        // Delete allocation
        // TODO
    }
}
