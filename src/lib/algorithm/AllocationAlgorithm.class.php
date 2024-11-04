<?php

class AllocationAlgorithm {
    /** @var AlgoCourseData[] $courses */
    private array $courses = [];

    /** @var AlgoUserData[] $users */
    private array $users = [];

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
        $this->linkUsersToCourses(true);

        // Coarse allocation of users to courses
        foreach($this->getSortedCourses() as $course) {
            $course->coarseUserAllocation();
        }
    }

    private function loadCoursesFromDatabase(): void {
        $courses = Course::dao()->getObjects();
        foreach($courses as $course) {
            $this->courses[] = AlgoCourseData::fromDatabaseObject($course);
        }
    }

    private function loadUsersFromDatabase(): void {
        $users = User::dao()->getObjects([
            "permissionLevel" => PermissionLevel::USER->value
        ]);
        foreach($users as $user) {
            $this->users[] = AlgoUserData::fromDatabaseObject($user);
        }
    }

    private function linkUsersToCourses(bool $ignoreCourseLeaders = false): void {
        foreach($this->users as $user) {
            $user->loadLeadingCourse();
            // In the exploratory phase, we do not need to load the chosen courses of course leaders
            if(!$ignoreCourseLeaders || $user->getLeadingCourse() === null) {
                $user->loadChosenCourses();
            }
        }
    }

    public function getSortedCourses(): array {
        $sortedCourses = $this->courses; // Shallow copy of the course array, so that it can be sorted in-place
        usort($sortedCourses, function(AlgoCourseData $a, AlgoCourseData $b) {
            return $a->getRelativeInterestRate() <=> $b->getRelativeInterestRate();
        });

        return $sortedCourses;
    }

    public function getUnallocatedUsers(bool $ignoreCourseLeaders = false): array {
        return array_filter($this->users, function(AlgoUserData $user) use ($ignoreCourseLeaders) {
            return !$user->isAllocated() && (!$ignoreCourseLeaders || $user->getLeadingCourse() === null);
        });
    }
}
