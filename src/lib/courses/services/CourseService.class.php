<?php

namespace app\courses;

use \app\users\User;
use \app\users\PermissionLevel;
use \app\settings\SystemStatus;

class CourseService {
    public static function delete(Course $course): void {
        // Delete all choices for this course
        $choices = \app\choices\ChoiceService::getChoicesForCourse($course);
        foreach($choices as $choice) {
            \app\choices\ChoiceService::delete($choice);
        }

        // Delete all assignments for this course
        $assignments = \app\assignments\AssignmentService::getAssignmentsOfCourse($course);
        foreach($assignments as $assignment) {
            \app\assignments\AssignmentService::delete($assignment);
        }

        // Reset the leading course id for all course leaders of this course
        $courseLeaders = self::getCourseLeaders($course);
        foreach($courseLeaders as $courseLeader) {
            $courseLeader->setLeadingCourseId(null);
            User::dao()->save($courseLeader);
        }

        Course::dao()->delete($course);
    }

    public function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return Course::dao()->getObject([ "id" => $numericId ]) instanceof Course;
    }

    public static array $courseLeadersCache = [];
    public static function getCourseLeaders(Course $course): array {
        if(self::$courseLeadersCache[$course->getId()] === null) {
            self::$courseLeadersCache[$course->getId()] = User::dao()->getObjects([
                "leadingCourseId" => $course->getId(),
                "permissionLevel" => PermissionLevel::USER->value
            ]);
        }

        return self::$courseLeadersCache[$course->getId()];
    }

    public static array $assignedUsersCache = [];
    public static function getAssignedUsers(Course $course): array {
        if(self::$assignedUsersCache[$course->getId()] === null) {
            $assignments = \app\assignments\AssignmentService::getAssignmentsOfCourse($course);
            self::$assignedUsersCache[$course->getId()] = [];
            foreach($assignments as $assignment) {
                self::$assignedUsersCache[$course->getId()][] = $assignment->getUser();
            }
        }

        return self::$assignedUsersCache[$course->getId()];
    }

    public static function getAssignedParticipants(Course $course): array {
        $assignedUsers = self::getAssignedUsers($course);
        return array_filter($assignedUsers, function(User $user) use ($course) {
            return $user->getLeadingCourseId() !== $course->getId();
        });
    }

    public static function isSpaceLeft(Course $course): bool {
        $participants = self::getAssignedParticipants($course);
        $participantCount = count($participants);
        return $participantCount < $course->getMaxParticipants();
    }

    public static function isCancelled(Course $course): bool {
        $algorithmComplete = SystemStatus::dao()->get("coursesAssigned") === "true";
        $participants = \app\assignments\AssignmentService::getAssignmentsOfCourse($course);
        return $algorithmComplete && empty($participants);
    }

    public static function getChoosableCourses(User $user, array $filter = [], string $orderBy = "id", bool $orderAsc = true, int $limit = -1, int $offset = 0): array {
        $courses = Course::dao()->getObjects($filter, $orderBy, $orderAsc, $limit, $offset);
        return array_filter($courses, function($course) use ($user) {
            return $course->canChooseCourse($user);
        });
    }
}
