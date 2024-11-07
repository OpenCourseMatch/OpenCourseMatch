<?php

class AlgoUtil {
    public static function setAllocation(AlgoUserData $user, AlgoCourseData $course) {
        if($user->isAllocated()) {
            $allocatedCourse = $user->getAllocatedCourse();
            $allocatedCourse->removeParticipant($user);
        }

        $allocatedAsCourseLeader = $course === $user->getLeadingCourse();

        $user->allocateToCourse($course, $allocatedAsCourseLeader);
        $course->addParticipant($user);
    }
}
