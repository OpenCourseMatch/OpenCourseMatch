<?php

class AlgoUtil {
    public static function setAssignmentStatus(bool $complete): void {
        SystemStatus::dao()->set("algorithmRunning", $complete ? "false" : "true");
        SystemStatus::dao()->set("coursesAssigned", $complete ? "true" : "false");
    }

    public static function resetDatabaseAssignments(): void {
        $assignments = Assignment::dao()->getObjects();
        foreach($assignments as $assignment) {
            Assignment::dao()->delete($assignment);
        }
    }

    public static function setAssignment(AlgoUserData $user, ?AlgoCourseData $course) {
        if($user->isAssigned()) {
            $assignedCourse = $user->getAssignedCourse();
            $assignedCourse->removeParticipant($user);
        }

        if($course === null) {
            $user->assignToCourse(null, false);
            return;
        }

        $assignedAsCourseLeader = $course === $user->getLeadingCourse();

        $user->assignToCourse($course, $assignedAsCourseLeader);
        $course->addParticipant($user);
    }
}
