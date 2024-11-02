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
        // TODO: Min participants
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
    private array $participants = [];

    public function addCourseLeader(AlgoUserData $user): void {
        $this->courseLeaders[] = $user;
    }

    public function addInterestedUser(AlgoUserData $user): void {
        $this->interestedUsers[] = $user;
    }

    public function getRelativeInterestRate(): float {
        return count($this->interestedUsers) / $this->maxParticipants;
    }
}
