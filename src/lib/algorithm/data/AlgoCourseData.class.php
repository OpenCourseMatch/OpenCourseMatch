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
            throw new AllocationAlgorithmException("Trying to access non-existing course with ID {$id}");
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

    public function addCourseLeader(AlgoUserData $user): void {
        $this->courseLeaders[] = $user;
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

    public function isSpaceLeft(): bool {
        return count($this->participants) < $this->maxParticipants;
    }

    public function getRelativeInterestRate(): float {
        return (count($this->interestedUsers) - count($this->participants)) / $this->maxParticipants;
    }

    public function coarseUserAllocation(): void {
        $sortedUsers = $this->interestedUsers; // Shallow copy of the user array, so that it can be sorted in-place
        uasort($sortedUsers, function(AlgoUserData $a, AlgoUserData $b) {
            // First sorting criterion: Remaining allocation probability
            // A lower probability results in the user being sorted earlier
            $aProb = $a->getAllocationProbability();
            $bProb = $b->getAllocationProbability();
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

        // Iterate over the sorted users and try to allocate them to the course
        foreach($sortedUsers as $user) {
            if($this->isSpaceLeft() && !$user->isAllocated()) {
                AlgoUtil::setAllocation($user, $this);
            }
        }
    }
}
