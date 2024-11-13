<?php

class AllocationAlgorithm {
    /** @var AlgoCourseData[] $courses */
    private array $courses = [];

    /** @var AlgoUserData[] $users */
    private array $users = [];

    /**
     * Run the course allocation algorithm
     * @return void
     * @throws AllocationAlgorithmException
     */
    public function run() {
        Logger::getLogger("AllocationAlgorithm")->info("Starting allocation algorithm");

        //********************
        //* PHASE 0: Initialization
        //********************
        Logger::getLogger("AllocationAlgorithm")->info("PHASE 0: Initialization");
        // Load data from database
        $this->loadCoursesFromDatabase();
        $this->loadUsersFromDatabase();

        // Reconstruct relationships between users and courses from database
        $this->linkUsersToCourses(false);

        //********************
        //* PHASE 1: Exploratory course allocation
        //***********+********
        Logger::getLogger("AllocationAlgorithm")->info("PHASE 1: Exploratory course allocation");
        // Coarse allocation of users to courses
        Logger::getLogger("AllocationAlgorithm")->trace("Coarse allocation of users to courses");
        foreach($this->getCoursesSortedByRelativeInterestRate() as $course) {
            $course->coarseUserAllocation();
        }
        $this->logAllocation();

        // Allocate unallocated users by finding allocation chains
        Logger::getLogger("AllocationAlgorithm")->trace("Allocate unallocated users by finding allocation chains");
        foreach($this->getUnallocatedUsers(false) as $user) {
            $allocationChain = $user->findAllocationChain();
            if(empty($allocationChain)) {
                continue;
            }

            // Allocate the user by reallocating the users in the allocation chain
            foreach($allocationChain as $chainItem) {
                AlgoUtil::setAllocation($chainItem["user"], $chainItem["course"]);
            }
        }
        $this->logAllocation();

        // Fine-tune the allocation by reallocating users to courses with higher choice priority
        Logger::getLogger("AllocationAlgorithm")->trace("Fine-tune the allocation by reallocating users to courses with higher choice priority");
        $iterations = 0;
        do {
            $swappedUsers = 0;
            $iterations++;
            foreach($this->getAllocatedUsersSortedByPriority() as $user) {
                $currentPriority = $user->getCoursePriority($user->getAllocatedCourse());
                foreach($user->getChosenCoursesWithHigherPriority($currentPriority) as $course) {
                    if(!$course->isSpaceLeft()) {
                        continue;
                    }

                    $swappedUsers++;
                    AlgoUtil::setAllocation($user, $course);

                    // Break the inner loop, because the user has been allocated to a chosen course with the highest priority which is still available
                    // Because the chosen courses are indexed by priority, the highest priority is first
                    break;
                }
            }
        } while($swappedUsers > 0 || $iterations <= 10);
        $this->logAllocation();

        //********************
        //* PHASE 2: Choose courses to be cancelled and reset choices and allocations
        //********************
        Logger::getLogger("AllocationAlgorithm")->info("PHASE 2: Choose courses to be cancelled");
        foreach($this->courses as $course) {
            if(!$course->hasEnoughParticipants()) {
                $course->setCancelled();
                Logger::getLogger("AllocationAlgorithm")->trace("Course {$course->id} has been cancelled");
            }
            $course->resetUserLists();
        }
        foreach($this->users as $user) {
            AlgoUtil::setAllocation($user, null);
        }
        $this->linkUsersToCourses(false);
    }

    /**
     * Build an array of AlgoCourseData objects from the courses in the database
     * @return void
     */
    private function loadCoursesFromDatabase(): void {
        $courses = Course::dao()->getObjects();
        foreach($courses as $course) {
            $this->courses[] = AlgoCourseData::fromDatabaseObject($course);
        }
    }

    /**
     * Build an array of AlgoUserData objects from the users in the database
     * @return void
     */
    private function loadUsersFromDatabase(): void {
        $users = User::dao()->getObjects([
            "permissionLevel" => PermissionLevel::USER->value
        ]);
        foreach($users as $user) {
            $this->users[] = AlgoUserData::fromDatabaseObject($user);
        }
    }

    /**
     * Load the leading course and chosen courses of all users
     * @param bool $loadChoiceForCourseLeaders Whether the chosen courses of course leaders should be loaded
     * @return void
     * @throws AllocationAlgorithmException
     */
    private function linkUsersToCourses(bool $loadChoiceForCourseLeaders): void {
        foreach($this->users as $user) {
            $user->resetLinkedObjects();
            $user->loadLeadingCourse();
            // In the exploratory phase, we do not need to load the chosen courses of course leaders
            if($loadChoiceForCourseLeaders || $user->getLeadingCourse() === null) {
                $user->loadChosenCourses();
            }
        }
    }

    /**
     * Get all courses sorted by their relative interest rate in ascending order
     * @return AlgoCourseData[]
     */
    public function getCoursesSortedByRelativeInterestRate(): array {
        $sortedCourses = $this->courses; // Shallow copy of the course array, so that it can be sorted in-place
        usort($sortedCourses, function(AlgoCourseData $a, AlgoCourseData $b) {
            return $a->getRelativeInterestRate() <=> $b->getRelativeInterestRate();
        });

        return $sortedCourses;
    }

    /**
     * Get all users that have not been allocated to a course yet
     * @param bool $includeCourseLeaders Whether (unallocated) course leaders should be included in the returned array
     * @return AlgoUserData[]
     * @throws AllocationAlgorithmException
     */
    public function getUnallocatedUsers(bool $includeCourseLeaders): array {
        return array_filter($this->users, function(AlgoUserData $user) use ($includeCourseLeaders) {
            return !$user->isAllocated() && ($includeCourseLeaders || $user->getLeadingCourse() === null);
        });
    }

    /**
     * Get all users that have been allocated to a course, sorted by the choice priority of the course in descending order
     * @return AlgoUserData[]
     * @throws AllocationAlgorithmException
     */
    public function getAllocatedUsersSortedByPriority(): array {
        $sortedUsers = array_filter($this->users, function(AlgoUserData $user) {
            return $user->isAllocated();
        }); // Filtered copy of the user array, so that it can be sorted in-place
        usort($sortedUsers, function(AlgoUserData $a, AlgoUserData $b) {
            return $b->getCoursePriority($b->getAllocatedCourse()) <=> $a->getCoursePriority($a->getAllocatedCourse());
        });
        return $sortedUsers;
    }

    /**
     * Output allocation statistics to the logfile
     * @return void
     * @throws AllocationAlgorithmException
     */
    public function logAllocation(): void {
        $courseLeaders = 0;
        foreach($this->courses as $course) {
            if($course->isCancelled()) {
                Logger::getLogger("AllocationAlgorithm")->info("Course {$course->id}: CANCELLED");
                continue;
            }

            $courseLeaders += count($course->getCourseLeaders());

            Logger::getLogger("AllocationAlgorithm")->info("Course {$course->id}: " . count($course->getParticipants()) . " / {$course->maxParticipants} (Min {$course->minParticipants})");
        }

        $message = "Users: ";
        $message .= count($this->users) . " in total, ";
        $message .= count($this->getUnallocatedUsers(true)) . " unallocated (including course leaders), ";
        $message .= count($this->getUnallocatedUsers(false)) . " unallocated (excluding course leaders), ";
        $message .= $courseLeaders . " course leaders";

        Logger::getLogger("AllocationAlgorithm")->info($message);
    }
}
