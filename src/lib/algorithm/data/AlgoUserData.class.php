<?php

class AlgoUserData {
    private static array $instances = [];

    public static function fromDatabaseObject(User $user): AlgoUserData {
        if(isset(self::$instances[$user->getId()])) {
            return self::$instances[$user->getId()];
        }

        $data = new AlgoUserData();
        $data->databaseObject = $user;
        $data->id = $user->getId();
        $data->clearance = $user->getGroup()?->getClearance() ?? 0;

        self::$instances[$user->getId()] = $data;

        return $data;
    }

    public static function getUsers(): array {
        return self::$instances;
    }

    public static function getUser(int $id): self {
        if(!isset(self::$instances[$id])) {
            throw new AllocationAlgorithmException("Trying to access non-existing user with ID {$id}");
        }

        return self::$instances[$id];
    }

    private User $databaseObject;

    public int $id;
    public int $clearance;

    private ?AlgoCourseData $leadingCourse = null;

    /** @var AlgoCourseData[] $interestedCourses */
    private array $interestedCourses = [];

    public function loadLeadingCourse(): void {
        if($this->databaseObject->getLeadingCourse() === null) {
            return;
        }

        $this->leadingCourse = AlgoCourseData::getCourse($this->databaseObject->getLeadingCourse()->getId());
        $this->leadingCourse->addCourseLeader($this);
    }

    public function loadChosenCourses(): void {
        $choices = $this->databaseObject->getChoices();
        foreach($choices as $priority => $choice) {
            if($choice === null) {
                continue;
            }

            $course = AlgoCourseData::getCourse($choice->getCourseId());
            $this->interestedCourses[$priority] = $course;
            $course->addInterestedUser($this);
        }
    }
}
