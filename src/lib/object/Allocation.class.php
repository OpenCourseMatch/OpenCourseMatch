<?php

class Allocation extends GenericObject {
    public ?int $userId = null;
    public ?int $courseId = null;

    private ?User $user = null;
    private ?Course $course = null;

    public function getUserId(): ?int {
        return $this->userId;
    }

    public function setUserId(?int $userId): void {
        $this->userId = $userId;
    }

    public function getCourseId(): ?int {
        return $this->courseId;
    }

    public function setCourseId(?int $courseId): void {
        $this->courseId = $courseId;
    }

    public function getUser(): ?User {
        if(!$this->user) {
            $this->user = User::dao()->getObject(["id" => $this->getUserId()]);
        }

        return $this->user;
    }

    public function getCourse(): ?Course {
        if(!$this->course) {
            $this->course = Course::dao()->getObject(["id" => $this->getCourseId()]);
        }

        return $this->course;
    }

    public function assignedAsCourseLeader(): bool {
        return $this->getUser()->getLeadingCourseId() !== null && $this->getUser()->getLeadingCourseId() === $this->getCourseId();
    }
}
