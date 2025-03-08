<?php

class Course extends GenericObject {
    public ?string $title = null;
    public ?string $organizer = null;
    public ?int $minClearance = null;
    public ?int $maxClearance = null;
    public ?int $minParticipants = null;
    public ?int $maxParticipants = null;

    private ?array $users = null;
    private ?array $participants = null;
    private ?array $courseLeaders = null;

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
        $clearancePassed = $this->isGroupAllowed($user->getGroup());
        $notLeadingCoursePassed = $user->getLeadingCourseId() !== $this->getId();

        return $clearancePassed && $notLeadingCoursePassed;
    }

    public function isGroupAllowed(?Group $group = null): bool {
        $clearance = 0;
        if($group !== null) {
            $clearance = $group->getClearance();
        }

        $minClearancePassed = $clearance >= $this->getMinClearance();
        $maxClearancePassed = $this->getMaxClearance() === null || $clearance <= $this->getMaxClearance();

        return $minClearancePassed && $maxClearancePassed;
    }

    public function isCancelled(): bool {
        $algorithmComplete = SystemStatus::dao()->get("coursesAssigned") === "true";
        $participants = Assignment::dao()->getObjects(["courseId" => $this->getId()]);
        return $algorithmComplete && empty($participants);
    }

    public function getAssignedUsers(): array {
        if($this->users === null) {
            $assignments = Assignment::dao()->getObjects(["courseId" => $this->getId()]);
            $this->users = array_map(function(Assignment $assignment) {
                return $assignment->getUser();
            }, $assignments);
        }

        return $this->users;
    }

    public function getAssignedParticipants(): array {
        if($this->participants === null) {
            $users = $this->getAssignedUsers();
            $this->participants = array_filter($users, function(User $user) {
                return $user->getLeadingCourseId() !== $this->getId();
            });
        }

        return $this->participants;
    }

    public function getAllCourseLeaders(): array {
        if($this->courseLeaders === null) {
            $this->courseLeaders = User::dao()->getObjects([
                "leadingCourseId" => $this->getId(),
                "permissionLevel" => PermissionLevel::USER->value
            ]);
        }

        return $this->courseLeaders;
    }

    public function isSpaceLeft(): bool {
        $participants = $this->getAssignedParticipants();
        $participantCount = count($participants);
        return $participantCount < $this->getMaxParticipants();
    }

    public function preDelete(): void {
        // Delete all choices for this course
        $choices = Choice::dao()->getObjects(["courseId" => $this->getId()]);
        foreach($choices as $choice) {
            Choice::dao()->delete($choice);
        }

        // Delete all assignments for this course
        // TODO
    }
}
