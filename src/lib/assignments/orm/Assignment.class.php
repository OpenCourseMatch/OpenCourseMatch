<?php

namespace app\assignments;

class Assignment extends \struktal\ORM\GenericEntity {
    public ?int $userId = null;
    public ?int $courseId = null;

    private ?\app\users\User $user = null;
    private ?\app\courses\Course $course = null;

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

    public function getUser(): ?\app\users\User {
        if(!$this->user) {
            $this->user = \app\users\User::dao()->getObject(["id" => $this->getUserId()]);
        }

        return $this->user;
    }

    public function getCourse(): ?\app\courses\Course {
        if(!$this->course) {
            $this->course = \app\courses\Course::dao()->getObject(["id" => $this->getCourseId()]);
        }

        return $this->course;
    }

    public function assignedAsCourseLeader(): bool {
        return $this->getUser()->getLeadingCourseId() !== null && $this->getUser()->getLeadingCourseId() === $this->getCourseId();
    }
}
