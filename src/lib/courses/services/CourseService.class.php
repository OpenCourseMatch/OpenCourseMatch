<?php

namespace app\courses;

class CourseService {
    public static function isGroupAllowedForCourse(Course $course, ?\app\groups\Group $group): bool {
        $clearance = 0;
        if($group instanceof \app\groups\Group) {
            $clearance = $group->getClearance();;
        }

        $minClearancePassed = $clearance >= $course->getMinClearance();
        $maxClearancePassed = $course->getMaxClearance() === null || $clearance <= $course->getMaxClearance();

        return $minClearancePassed && $maxClearancePassed;
    }

    public static function canChooseCourse(Course $course, \app\users\User $user): bool {
        $clearancePassed = self::isGroupAllowedForCourse($course, $user->getGroup());
        $notLeadingCoursePassed = $user->getLeadingCourseId() !== $course->getId();

        return $clearancePassed && $notLeadingCoursePassed;
    }

    public static function isCourseCancelled(Course $course): bool {
        $algorithmComplete = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";
        $assignments = $course->getAssignments();
        return $algorithmComplete && empty($assignments);
    }

    public static function isSpaceLeft(Course $course): bool {
        $participants = self::getAssignedUsers($course, true, false);
        $participantsCount = count($participants);
        return $participantsCount < $course->getMaxParticipants();
    }

    public static function getAssignedUsers(Course $course, bool $excludeCourseLeaders = false, bool $excludeParticipants = false): array {
        $assignments = $course->getAssignments();
        $users = [];
        foreach($assignments as $assignment) {
            if(!$assignment instanceof \app\assignments\Assignment) {
                continue;
            }

            $user = $assignment->getUser();
            if(!$user instanceof \app\users\User) {
                continue;
            }

            // Exclude course leaders
            if($excludeCourseLeaders && $user->getLeadingCourseId() === $course->getId()) {
                continue;
            }

            // Exclude participants
            if($excludeParticipants && $user->getLeadingCourseId() !== $course->getId()) {
                continue;
            }
        }
        return $users;
    }

    public static function getCourseLeaders(Course $course): array {
        return \app\users\User::dao()->getObjects([
            "leadingCourseId" => $course->getId(),
            "permissionLevel" => \app\users\PermissionLevel::USER
        ]);
    }
}
