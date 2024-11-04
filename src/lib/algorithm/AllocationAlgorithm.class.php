<?php

class AllocationAlgorithm {
    /** @var AlgoCourseData[] $courses */
    private array $courses = [];

    /** @var AlgoUserData[] $users */
    private array $users = [];

    public function run() {
        // Load data from database
        $this->loadCoursesFromDatabase();
        $this->loadUsersFromDatabase();

        // Reconstruct relationships between users and courses
        $this->linkUsersToCourses();

        // TODO: Continue...

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

    private function linkUsersToCourses(): void {
        foreach($this->users as $user) {
            $user->loadLeadingCourse();
            // Skip loading interested courses for course leaders for now.
            // We assume that their courses take place.
            // If we notice later in the algorithm that a course has too few participants, we will load the interested courses for its course leaders then.
            if($user->getLeadingCourse() === null) {
                $user->loadChosenCourses();
            }
        }
    }
}
