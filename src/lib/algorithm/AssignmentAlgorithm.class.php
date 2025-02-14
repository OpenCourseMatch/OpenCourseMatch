<?php

class AssignmentAlgorithm {
    /** @var AlgoCourseData[] $courses */
    private array $courses = [];

    /** @var AlgoUserData[] $users */
    private array $users = [];

    /**
     * Run the course assignment algorithm
     * @return void
     * @throws AssignmentAlgorithmException
     */
    public function run() {
        // Set the assignment algorithm status to running
        AlgoUtil::setAssignmentStatus(false);

        Logger::getLogger("AssignmentAlgorithm")->info("Starting assignment algorithm");

        //********************
        //* PHASE 0: Initialization
        //********************
        Logger::getLogger("AssignmentAlgorithm")->info("PHASE 0: Initialization");
        // Delete old assignments from database
        AlgoUtil::resetDatabaseAssignments();

        // Load data from database
        $this->loadCoursesFromDatabase();
        $this->loadUsersFromDatabase();

        // Reconstruct relationships between users and courses from database
        $this->linkUsersToCourses(false);

        //********************
        //* PHASE 1: Exploratory course assignment
        //***********+********
        Logger::getLogger("AssignmentAlgorithm")->info("PHASE 1: Exploratory course assignment");
        $this->probabilityAssignment();
        $this->chainingAssignment();
        $this->enhanceAssignment();

        //********************
        //* PHASE 2: Choose courses to be cancelled and reset choices and assignments
        //********************
        Logger::getLogger("AssignmentAlgorithm")->info("PHASE 2: Choose courses to be cancelled");
        foreach($this->courses as $course) {
            if(!$course->hasEnoughParticipants()) {
                $course->setCancelled();
                Logger::getLogger("AssignmentAlgorithm")->trace("Course {$course->id} has been cancelled");
            }
            $course->resetUserLists();
        }
        foreach($this->users as $user) {
            AlgoUtil::setAssignment($user, null);
        }
        $this->linkUsersToCourses(false);

        //********************
        //* PHASE 3: Confident course assignment
        //********************
        Logger::getLogger("AssignmentAlgorithm")->info("PHASE 3: Confident course assignment");
        $this->probabilityAssignment();
        $this->chainingAssignment();
        $this->enhanceAssignment();

        //********************
        //* PHASE 4: Finalize assignment
        //********************
        Logger::getLogger("AssignmentAlgorithm")->info("PHASE 4: Finalize assignment");
        // Choose courses to be cancelled
        Logger::getLogger("AssignmentAlgorithm")->trace("Choose courses to be cancelled");
        foreach($this->getCoursesSortedByRelativeInterestRate() as $course) {
            if(!$course->hasEnoughParticipants() && !$course->isCancelled()) {
                $course->setCancelled();
                Logger::getLogger("AssignmentAlgorithm")->trace("Course {$course->id} has been cancelled");

                $users = array_merge($course->getParticipants(), $course->getCourseLeaders());
                foreach($users as $user) {
                    AlgoUtil::setAssignment($user, null);
                }
                $this->linkUsersToCourses(false, $users);

                // Reset user lists of the course
                $course->resetUserLists();
            }
        }

        // Reassign users to courses
        $this->chainingAssignment();
        $this->enhanceAssignment();

        //********************
        //* PHASE 5: Save assignments to database
        //********************
        Logger::getLogger("AssignmentAlgorithm")->info("PHASE 5: Save assignments to database");
        foreach($this->users as $user) {
            $user->saveAssignment();
        }

        // Set the assignment algorithm status to complete
        AlgoUtil::setAssignmentStatus(true);

        Logger::getLogger("AssignmentAlgorithm")->info("Completed assignment algorithm");
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
     * @param AlgoUserData[]|null $users The users for which the courses should be loaded, or null for all users
     * @return void
     * @throws AssignmentAlgorithmException
     */
    private function linkUsersToCourses(bool $loadChoiceForCourseLeaders, ?array $users = null): void {
        if($users === null) {
            $users = $this->users;
        }

        foreach($users as $user) {
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
    private function getCoursesSortedByRelativeInterestRate(): array {
        $sortedCourses = $this->courses; // Shallow copy of the course array, so that it can be sorted in-place
        usort($sortedCourses, function(AlgoCourseData $a, AlgoCourseData $b) {
            return $a->getRelativeInterestRate() <=> $b->getRelativeInterestRate();
        });

        return $sortedCourses;
    }

    /**
     * Get all users that have not been assigned to a course yet
     * @param bool $includeCourseLeaders Whether (unassigned) course leaders should be included in the returned array
     * @return AlgoUserData[]
     * @throws AssignmentAlgorithmException
     */
    private function getUnassignedUsers(bool $includeCourseLeaders): array {
        return array_filter($this->users, function(AlgoUserData $user) use ($includeCourseLeaders) {
            return !$user->isAssigned() && ($includeCourseLeaders || $user->getLeadingCourse() === null);
        });
    }

    /**
     * Get all users that have been assigned to a course, sorted by the choice priority of the course in descending order
     * @return AlgoUserData[]
     * @throws AssignmentAlgorithmException
     */
    private function getAssignedUsersSortedByPriority(): array {
        $sortedUsers = array_filter($this->users, function(AlgoUserData $user) {
            return $user->isAssigned();
        }); // Filtered copy of the user array, so that it can be sorted in-place
        usort($sortedUsers, function(AlgoUserData $a, AlgoUserData $b) {
            return $b->getCoursePriority($b->getAssignedCourse()) <=> $a->getCoursePriority($a->getAssignedCourse());
        });
        return $sortedUsers;
    }

    private function probabilityAssignment(): void {
        Logger::getLogger("AssignmentAlgorithm")->trace("Coarse assignment of users to courses using the probability-based approach");
        foreach($this->getCoursesSortedByRelativeInterestRate() as $course) {
            $course->coarseUserAssignment();
        }
        $this->logAssignment();
    }

    private function chainingAssignment(): void {
        Logger::getLogger("AssignmentAlgorithm")->trace("Assign unassigned users by finding assignment chains");
        foreach($this->getUnassignedUsers(false) as $user) {
            $assignmentChain = $user->findAssignmentChain();
            if(empty($assignmentChain)) {
                continue;
            }

            // Assign the user by reassigning the users in the assignment chain
            foreach($assignmentChain as $chainItem) {
                AlgoUtil::setAssignment($chainItem["user"], $chainItem["course"]);
            }
        }
        $this->logAssignment();
    }

    private function enhanceAssignment(): void {
        Logger::getLogger("AssignmentAlgorithm")->trace("Fine-tune the assignment by reassigning users to courses with higher choice priority");
        $iterations = 0;
        do {
            $swappedUsers = 0;
            $iterations++;
            foreach($this->getAssignedUsersSortedByPriority() as $user) {
                $currentPriority = $user->getCoursePriority($user->getAssignedCourse());
                foreach($user->getChosenCoursesWithHigherPriority($currentPriority) as $course) {
                    if(!$course->isSpaceLeft()) {
                        continue;
                    }

                    $swappedUsers++;
                    AlgoUtil::setAssignment($user, $course);

                    // Break the inner loop, because the user has been assigned to a chosen course with the highest priority which is still available
                    // Because the chosen courses are indexed by priority, the highest priority is first
                    break;
                }
            }
        } while($swappedUsers > 0 || $iterations <= 10);
        $this->logAssignment();
    }

    /**
     * Output assignment statistics to the logfile
     * @return void
     * @throws AssignmentAlgorithmException
     */
    private function logAssignment(): void {
        $courseLeaders = 0;
        foreach($this->courses as $course) {
            if($course->isCancelled()) {
                Logger::getLogger("AssignmentAlgorithm")->trace("Course {$course->id}: CANCELLED");
                continue;
            }

            $courseLeaders += count($course->getCourseLeaders());

            Logger::getLogger("AssignmentAlgorithm")->trace("Course {$course->id}: " . count($course->getParticipants()) . " / {$course->maxParticipants} (Min {$course->minParticipants})");
        }

        $message = "Users: ";
        $message .= count($this->users) . " in total, ";
        $message .= count($this->getUnassignedUsers(true)) . " unassigned (including course leaders), ";
        $message .= count($this->getUnassignedUsers(false)) . " unassigned (excluding course leaders), ";
        $message .= $courseLeaders . " course leaders";

        Logger::getLogger("AssignmentAlgorithm")->trace($message);
    }
}
