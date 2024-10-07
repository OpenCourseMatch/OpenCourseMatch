<?php

class Course extends GenericObject {
    public ?string $title = null;
    public ?string $organizer = null;
    public ?int $minClearance = null;
    public ?int $maxClearance = null;
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

    public function getMaxParticipants(): ?int {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(?int $maxParticipants): void {
        $this->maxParticipants = $maxParticipants;
    }

    public function canChooseProject(User $user): bool {
        $userClearance = 0;
        if($user->getGroup() !== null) {
            $userClearance = $user->getGroup()->getClearance();
        }

        return $userClearance >= $this->getMinClearance() && $userClearance <= $this->getMaxClearance();
    }
}
