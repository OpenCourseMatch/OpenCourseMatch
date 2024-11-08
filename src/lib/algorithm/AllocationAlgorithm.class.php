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
        //********************
        //* PHASE 0: Initialization
        //********************
        // Load data from database
        $this->loadCoursesFromDatabase();
        $this->loadUsersFromDatabase();

        //********************
        //* PHASE 1: Exploratory course allocation
        //***********+********
        // Reconstruct relationships between users and courses from database
        $this->linkUsersToCourses(false);

        // Coarse allocation of users to courses
        foreach($this->getCoursesSortedByRelativeInterestRate() as $course) {
            $course->coarseUserAllocation();
        }

        // Allocate unallocated users by finding allocation chains
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

        // Fine-tune the allocation by reallocating users to courses with higher choice priority
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
}
