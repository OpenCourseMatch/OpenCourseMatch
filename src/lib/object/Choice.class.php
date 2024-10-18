<?php

class Choice extends GenericObject {
    public ?int $userId = null;
    public ?int $courseId = null;
    public ?int $priority = null;

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
}
