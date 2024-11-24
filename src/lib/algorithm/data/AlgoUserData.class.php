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
    private bool $leadingCourseLoaded = false;

    /** @var AlgoCourseData[] $interestedCourses */
    private array $interestedCourses = [];
    private bool $interestedCoursesLoaded = false;

    private ?AlgoCourseData $allocatedCourse = null;
    private bool $allocatedAsLeader = false;
    private bool $allocated = false;

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
            throw new AllocationAlgorithmException("Trying to access leading course although it has not been loaded yet");
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
            throw new AllocationAlgorithmException("Trying to access chosen courses although they have not been loaded yet");
        }

        return array_filter($this->interestedCourses, function(AlgoCourseData $course) use ($priority) {
            // This comparison here is "<" and not ">" because the priorities are in descending order (0 is the highest priority)
            return $this->getCoursePriority($course) < $priority;
        });
    }

    public function getCoursePriority(AlgoCourseData $course): ?int {
        if(!$this->interestedCoursesLoaded) {
            throw new AllocationAlgorithmException("Trying to access course priority although interested courses have not been loaded yet");
        }

        return array_search($course, $this->interestedCourses, true);
    }

    public function allocateToCourse(?AlgoCourseData $course, bool $asLeader = false): void {
        $this->allocatedCourse = $course;
        $this->allocatedAsLeader = $asLeader;
        $this->allocated = $course instanceof AlgoCourseData;
    }

    public function isAllocated(): bool {
        return $this->allocated && $this->allocatedCourse instanceof AlgoCourseData;
    }

    public function getAllocatedCourse(): ?AlgoCourseData {
        if(!$this->allocated) {
            throw new AllocationAlgorithmException("Trying to access allocated course although user has not been allocated yet");
        }

        return $this->allocatedCourse;
    }

    public function isAllocatedAsLeader(): bool {
        if(!$this->allocated) {
            throw new AllocationAlgorithmException("Trying to access allocated leader status although user has not been allocated yet");
        }

        return $this->allocatedAsLeader;
    }

    public function resetLinkedObjects(): void {
        $this->leadingCourse = null;
        $this->leadingCourseLoaded = false;
        $this->interestedCourses = [];
        $this->interestedCoursesLoaded = false;
        $this->allocatedCourse = null;
        $this->allocatedAsLeader = false;
        $this->allocated = false;
    }

    public function getAllocationProbability(array $withoutCourses = []): float {
        if(!$this->interestedCoursesLoaded) {
            throw new AllocationAlgorithmException("Trying to access allocation probability although interested courses have not been loaded yet");
        }

        $evaluatedCourses = array_filter($this->interestedCourses, function(AlgoCourseData $course) use ($withoutCourses) {
            return !in_array($course, $withoutCourses, true);
        });

        return array_sum(array_map(function(AlgoCourseData $course) {
            return $course->getAllocationProbability();
        }, $evaluatedCourses));
    }

    public function findAllocationChain(array $coursesInChain = [], int $depth = -1): array {
        // Fast abort if the maximum depth was reached
        if($depth === 0) {
            return [];
        }

        // Don't re-check the same courses again to prevent loops
        $chosenCourses = array_filter($this->interestedCourses, function(AlgoCourseData $course) use ($coursesInChain) {
            return !in_array($course, $coursesInChain, true);
        });

        // Add some randomness to the allocation
        shuffle($chosenCourses);

        // Check if a direct reallocation is possible
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

        // Indirect allocations would be skipped anyway, faster abort
        if($depth === 1) {
            return [];
        }

        $newDepth = $depth === -1 ? -1 : $depth - 1;

        // Check if an indirect reallocation is possible
        foreach($chosenCourses as $course) {
            $users = $course->getParticipants();

            $newCoursesInChain = $coursesInChain;
            $newCoursesInChain[] = $course;

            foreach($users as $user) {
                $chain = $user->findAllocationChain($newCoursesInChain, $newDepth);
                if(!empty($chain)) {
                    $chain[] = [
                        "user" => $this,
                        "course" => $course
                    ];
                    return $chain;
                }
            }
        }

        // Couldn't find an allocation chain
        return [];
    }

    public function saveAllocation(): void {
        if($this->isAllocated()) {
            $allocation = new Allocation();
            $allocation->setUserId($this->id);
            $allocation->setCourseId($this->getAllocatedCourse()->id);
            Allocation::dao()->save($allocation);
            return;
        }

        if($this->getLeadingCourse() !== null && !$this->getLeadingCourse()->isCancelled()) {
            $allocation = new Allocation();
            $allocation->setUserId($this->id);
            $allocation->setCourseId($this->getLeadingCourse()->id);
            Allocation::dao()->save($allocation);
        }
    }
}
