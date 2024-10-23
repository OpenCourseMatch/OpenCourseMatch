<?php

class Choice extends GenericObject {
    public ?int $userId = null;
    public ?int $courseId = null;
    public ?int $priority = null;

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

    public function getPriority(): ?int {
        return $this->priority;
    }

    public function setPriority(?int $priority): void {
        $this->priority = $priority;
    }

    public function getCourse(): ?Course {
        if(!$this->course) {
            $this->course = Course::dao()->getObject(["id" => $this->getCourseId()]);
        }

        return $this->course;
    }
}
