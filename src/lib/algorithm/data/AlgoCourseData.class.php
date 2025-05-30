<?php

class AlgoCourseData {
    private static array $instances = [];

    public static function fromDatabaseObject(Course $course): AlgoCourseData {
        if(isset(self::$instances[$course->getId()])) {
            return self::$instances[$course->getId()];
        }

        $data = new AlgoCourseData();
        $data->databaseObject = $course;
        $data->id = $course->getId();
        $data->minClearance = $course->getMinClearance();
        $data->maxClearance = $course->getMaxClearance();
        $data->minParticipants = $course->getMinParticipants();
        $data->maxParticipants = $course->getMaxParticipants();

        self::$instances[$course->getId()] = $data;

        return $data;
    }

    public static function getCourses(): array {
        return self::$instances;
    }

    public static function getCourse(int $id): self {
        if(!isset(self::$instances[$id])) {
            throw new AssignmentAlgorithmException("Trying to access non-existing course with ID {$id}");
        }

        return self::$instances[$id];
    }

    private Course $databaseObject;

    public int $id;
    public int $minClearance;
    public int $maxClearance;
    public int $minParticipants = 0;
    public int $maxParticipants;

    /* @var AlgoUserData[] $courseLeaders Filled in AlgoUserData::fromDatabaseObject */
    private array $courseLeaders = [];

    /* @var AlgoUserData[] $interestedUsers Filled in AlgoUserData::loadChosenCourses */
    private array $interestedUsers = [];

    /** @var AlgoUserData[] $participants */
    private array $participants = [];

    /** @var bool $cancelled This variable is NOT reset */
    private bool $cancelled = false;

    public function addCourseLeader(AlgoUserData $user): void {
        if(!in_array($user, $this->courseLeaders)) {
            $this->courseLeaders[] = $user;
        }
    }

    public function getCourseLeaders(): array {
        return $this->courseLeaders;
    }

    public function addInterestedUser(AlgoUserData $user): void {
        $this->interestedUsers[] = $user;
    }

    public function addParticipant(AlgoUserData $user): void {
        $this->participants[] = $user;
    }

    public function removeParticipant(AlgoUserData $user): void {
        $this->participants = array_filter($this->participants, function(AlgoUserData $participant) use ($user) {
            return $participant !== $user;
        });
    }

    public function getParticipants(): array {
        return $this->participants;
    }

    public function hasEnoughParticipants(): bool {
        return count($this->participants) >= $this->minParticipants;
    }

    public function isSpaceLeft(): bool {
        $participantsWithoutCourseLeaders = array_filter($this->participants, function(AlgoUserData $participant) {
            return $participant->getLeadingCourse() !== $this;
        });
        return count($participantsWithoutCourseLeaders) < $this->maxParticipants;
    }

    public function resetUserLists(): void {
        $this->courseLeaders = [];
        $this->interestedUsers = [];
        $this->participants = [];
    }

    public function setCancelled(bool $cancelled = true): void {
        $this->cancelled = $cancelled;
    }

    public function isCancelled(): bool {
        return $this->cancelled;
    }

    public function getRelativeInterestRate(): float {
        return (count($this->interestedUsers) - count($this->participants)) / $this->maxParticipants;
    }

    public function getAssignmentProbability(): float {
        $relativeInterestRate = $this->getRelativeInterestRate();
        if($relativeInterestRate === 0.0) {
            return 1;
        }

        return 1 / $relativeInterestRate;
    }

    public function coarseUserAssignment(): void {
        if($this->isCancelled()) {
            return;
        }

        $sortedUsers = $this->interestedUsers; // Shallow copy of the user array, so that it can be sorted in-place
        uasort($sortedUsers, function(AlgoUserData $a, AlgoUserData $b) {
            // First sorting criterion: Remaining assignment probability
            // A lower probability results in the user being sorted earlier
            $aProb = $a->getAssignmentProbability();
            $bProb = $b->getAssignmentProbability();
            if($aProb !== $bProb) {
                return $aProb <=> $bProb;
            }

            // Second sorting criterion: Priority of the course to the user
            // A higher relevance results in the user being sorted earlier
            $aRelevance = $a->getCoursePriority($this);
            $bRelevance = $b->getCoursePriority($this);
            if($aRelevance !== $bRelevance) {
                return $bRelevance <=> $aRelevance;
            }

            // Third sorting criterion: Randomness
            $aRandom = rand();
            $bRandom = rand();
            return $aRandom <=> $bRandom;
        });

        // Iterate over the sorted users and try to assign them to the course
        foreach($sortedUsers as $user) {
            if($this->isSpaceLeft() && !$user->isAssigned()) {
                AlgoUtil::setAssignment($user, $this);
            }
        }
    }
}
