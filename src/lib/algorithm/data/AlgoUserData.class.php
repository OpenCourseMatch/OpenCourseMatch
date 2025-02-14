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
            throw new AssignmentAlgorithmException("Trying to access non-existing user with ID {$id}");
        }

        return self::$instances[$id];
    }

    private User $databaseObject;

    public int $id;
    public int $clearance;

    private ?AlgoCourseData $leadingCourse = null;
    private bool $leadingCourseLoaded = false;

    /** @var AlgoCourseData[] $interestedCourses */
    private array $interestedCourses = [];
    private bool $interestedCoursesLoaded = false;

    private ?AlgoCourseData $assignedCourse = null;
    private bool $assignedAsLeader = false;
    private bool $assigned = false;

    public function loadLeadingCourse(): void {
        if($this->databaseObject->getLeadingCourse() === null) {
            $this->leadingCourseLoaded = true;
            return;
        }

        $course = AlgoCourseData::getCourse($this->databaseObject->getLeadingCourse()->getId());
        if($course->isCancelled()) {
            $this->leadingCourseLoaded = true;
            return;
        }

        $this->leadingCourse = $course;
        $this->leadingCourse->addCourseLeader($this);
        $this->leadingCourseLoaded = true;
    }

    public function getLeadingCourse(): ?AlgoCourseData {
        if(!$this->leadingCourseLoaded) {
            throw new AssignmentAlgorithmException("Trying to access leading course although it has not been loaded yet");
        }

        return $this->leadingCourse;
    }

    public function loadChosenCourses(): void {
        $choices = $this->databaseObject->getChoices();
        foreach($choices as $priority => $choice) {
            if($choice === null) {
                continue;
            }

            $course = AlgoCourseData::getCourse($choice->getCourseId());
            if($course->isCancelled()) {
                continue;
            }

            $this->interestedCourses[$priority] = $course;
            $course->addInterestedUser($this);
        }

        $this->interestedCoursesLoaded = true;
    }

    public function getChosenCoursesWithHigherPriority(int $priority): array {
        if(!$this->interestedCoursesLoaded) {
            throw new AssignmentAlgorithmException("Trying to access chosen courses although they have not been loaded yet");
        }

        return array_filter($this->interestedCourses, function(AlgoCourseData $course) use ($priority) {
            // This comparison here is "<" and not ">" because the priorities are in descending order (0 is the highest priority)
            return $this->getCoursePriority($course) < $priority;
        });
    }

    public function getCoursePriority(AlgoCourseData $course): ?int {
        if(!$this->interestedCoursesLoaded) {
            throw new AssignmentAlgorithmException("Trying to access course priority although interested courses have not been loaded yet");
        }

        return array_search($course, $this->interestedCourses, true);
    }

    public function assignToCourse(?AlgoCourseData $course, bool $asLeader = false): void {
        $this->assignedCourse = $course;
        $this->assignedAsLeader = $asLeader;
        $this->assigned = $course instanceof AlgoCourseData;
    }

    public function isAssigned(): bool {
        return $this->assigned && $this->assignedCourse instanceof AlgoCourseData;
    }

    public function getAssignedCourse(): ?AlgoCourseData {
        if(!$this->assigned) {
            throw new AssignmentAlgorithmException("Trying to access assigned course although user has not been assigned yet");
        }

        return $this->assignedCourse;
    }

    public function isAssignedAsLeader(): bool {
        if(!$this->assigned) {
            throw new AssignmentAlgorithmException("Trying to access assigned leader status although user has not been assigned yet");
        }

        return $this->assignedAsLeader;
    }

    public function resetLinkedObjects(): void {
        $this->leadingCourse = null;
        $this->leadingCourseLoaded = false;
        $this->interestedCourses = [];
        $this->interestedCoursesLoaded = false;
        $this->assignedCourse = null;
        $this->assignedAsLeader = false;
        $this->assigned = false;
    }

    public function getAssignmentProbability(array $withoutCourses = []): float {
        if(!$this->interestedCoursesLoaded) {
            throw new AssignmentAlgorithmException("Trying to access assignment probability although interested courses have not been loaded yet");
        }

        $evaluatedCourses = array_filter($this->interestedCourses, function(AlgoCourseData $course) use ($withoutCourses) {
            return !in_array($course, $withoutCourses, true);
        });

        return array_sum(array_map(function(AlgoCourseData $course) {
            return $course->getAssignmentProbability();
        }, $evaluatedCourses));
    }

    public function findAssignmentChain(array $coursesInChain = [], int $depth = -1): array {
        // Fast abort if the maximum depth was reached
        if($depth === 0) {
            return [];
        }

        // Don't re-check the same courses again to prevent loops
        $chosenCourses = array_filter($this->interestedCourses, function(AlgoCourseData $course) use ($coursesInChain) {
            return !in_array($course, $coursesInChain, true);
        });

        // Add some randomness to the assignment
        shuffle($chosenCourses);

        // Check if a direct reassignment is possible
        foreach($chosenCourses as $course) {
            if($course->isSpaceLeft()) {
                return [
                    [
                        "user" => $this,
                        "course" => $course
                    ]
                ];
            }
        }

        // Indirect assignments would be skipped anyway, faster abort
        if($depth === 1) {
            return [];
        }

        $newDepth = $depth === -1 ? -1 : $depth - 1;

        // Check if an indirect reassignment is possible
        foreach($chosenCourses as $course) {
            $users = $course->getParticipants();

            $newCoursesInChain = $coursesInChain;
            $newCoursesInChain[] = $course;

            foreach($users as $user) {
                $chain = $user->findAssignmentChain($newCoursesInChain, $newDepth);
                if(!empty($chain)) {
                    $chain[] = [
                        "user" => $this,
                        "course" => $course
                    ];
                    return $chain;
                }
            }
        }

        // Couldn't find a reassignment chain
        return [];
    }

    public function saveAssignment(): void {
        // If the user was assigned, save the assignment directly
        if($this->isAssigned()) {
            $assignment = new Assignment();
            $assignment->setUserId($this->id);
            $assignment->setCourseId($this->getAssignedCourse()->id);
            Assignment::dao()->save($assignment);
            return;
        }

        // If the user is leading a course which is not cancelled, assign the user to it
        if($this->getLeadingCourse() !== null && !$this->getLeadingCourse()->isCancelled()) {
            $assignment = new Assignment();
            $assignment->setUserId($this->id);
            $assignment->setCourseId($this->getLeadingCourse()->id);
            Assignment::dao()->save($assignment);
        }
    }
}
