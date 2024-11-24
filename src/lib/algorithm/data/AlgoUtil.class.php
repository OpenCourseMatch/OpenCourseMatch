<?php

class AlgoUtil {
    public static function setAllocationStatus(bool $complete): void {
        SystemStatus::dao()->set("algorithmRunning", $complete ? "false" : "true");
        SystemStatus::dao()->set("coursesAssigned", $complete ? "true" : "false");
    }

    public static function resetDatabaseAllocations(): void {
        $allocations = Allocation::dao()->getObjects();
        foreach($allocations as $allocation) {
            Allocation::dao()->delete($allocation);
        }
    }

    public static function setAllocation(AlgoUserData $user, ?AlgoCourseData $course) {
        if($user->isAllocated()) {
            $allocatedCourse = $user->getAllocatedCourse();
            $allocatedCourse->removeParticipant($user);
        }

        if($course === null) {
            $user->allocateToCourse(null, false);
            return;
        }

        $allocatedAsCourseLeader = $course === $user->getLeadingCourse();

        $user->allocateToCourse($course, $allocatedAsCourseLeader);
        $course->addParticipant($user);
    }
}
