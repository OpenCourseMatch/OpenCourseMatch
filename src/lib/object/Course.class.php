<?php

class Course extends GenericObject {
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

    public function canChooseCourse(User $user): bool {
        $userClearance = 0;
        if($user->getGroup() !== null) {
            $userClearance = $user->getGroup()->getClearance();
        }

        $minClearancePassed = $userClearance >= $this->getMinClearance();
        $maxClearancePassed = $this->getMaxClearance() === null || $userClearance <= $this->getMaxClearance();
        $notLeadingCoursePassed = $user->getLeadingCourseId() !== $this->getId();

        return $minClearancePassed && $maxClearancePassed && $notLeadingCoursePassed;
    }

    public function isCancelled(): bool {
        $algorithmComplete = SystemStatus::dao()->get("coursesAssigned") === "true";
        $participants = Allocation::dao()->getObjects(["courseId" => $this->getId()]);
        return $algorithmComplete && !empty($participants);
    }

    public function preDelete(): void {
        // Delete all choices for this course
        $choices = Choice::dao()->getObjects(["courseId" => $this->getId()]);
        foreach($choices as $choice) {
            Choice::dao()->delete($choice);
        }

        // Delete all allocations for this course
        // TODO
    }
}
