<?php

namespace app\courses;

class Course extends \struktal\ORM\GenericEntity {
    public ?string $title = null;
    public ?string $organizer = null;
    public ?int $minClearance = null;
    public ?int $maxClearance = null;
    public ?int $minParticipants = null;
    public ?int $maxParticipants = null;

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    public function getOrganizer(): ?string {
        return $this->organizer;
    }

    public function setOrganizer(?string $organizer): void {
        $this->organizer = $organizer;
    }

    public function getMinClearance(): ?int {
        return $this->minClearance;
    }

    public function setMinClearance(?int $minClearance): void {
        $this->minClearance = $minClearance;
    }

    public function getMaxClearance(): ?int {
        return $this->maxClearance;
    }

    public function setMaxClearance(?int $maxClearance): void {
        $this->maxClearance = $maxClearance;
    }

    public function getMinParticipants(): ?int {
        return $this->minParticipants;
    }

    public function setMinParticipants(?int $minParticipants): void {
        $this->minParticipants = $minParticipants;
    }

    public function getMaxParticipants(): ?int {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(?int $maxParticipants): void {
        $this->maxParticipants = $maxParticipants;
    }

    public function isGroupAllowed(?\app\groups\Group $group = null): bool {
        return CourseService::isGroupAllowedForCourse($this, $group);
    }

    public function canChooseCourse(\app\users\User $user): bool {
        return CourseService::canChooseCourse($this, $user);
    }

    public function isCancelled(): bool {
        return CourseService::isCourseCancelled($this);
    }

    public function isSpaceLeft(): bool {
        return CourseService::isSpaceLeft($this);
    }

    public function getChoices(): array {
        return \app\choices\ChoiceService::getChoicesForCourse($this);
    }

    public function getAssignments(): array {
        return \app\assignments\AssignmentService::getAssignmentsForCourse($this);
    }

    public function getAssignedUsers(): array {
        return CourseService::getAssignedUsers($this, false, false);
    }

    public function getAssignedParticipants(): array {
        return CourseService::getAssignedUsers($this, true, false);
    }

    public function getAllCourseLeaders(): array {
        return CourseService::getCourseLeaders($this);
    }

    public function preDelete(): void {
        // Delete all choices for this course
        $choices = $this->getChoices();
        foreach($choices as $choice) {
            \app\choices\Choice::dao()->delete($choice);
        }

        // Delete all assignments for this course
        $assignments = $this->getAssignments();
        foreach($assignments as $assignment) {
            \app\assignments\Assignment::dao()->delete($assignment);
        }

        // Delete all course leaders for this course
        $courseLeaders = $this->getAllCourseLeaders();
        foreach($courseLeaders as $courseLeader) {
            $courseLeader->setLeadingCourseId(null);
            \app\users\User::dao()->save($courseLeader);
        }
    }
}
