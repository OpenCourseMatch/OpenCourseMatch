<?php

namespace app\assignments;

class AssignmentService {
    public static function getAssignmentForUser(\app\users\User $user): ?Assignment {
        $assignment = Assignment::dao()->getObject([
            "userId" => $user->getId()
        ]);

        if($assignment instanceof Assignment) {
            return $assignment;
        }

        return null;
    }

    public static function getAssignmentsForCourse(\app\courses\Course $course): array {
        return Assignment::dao()->getObjects([
            "courseId" => $course->getId()
        ]);
    }

    public static function getAssignedCourseForUser(\app\users\User $user): ?\app\courses\Course {
        $assignment = self::getAssignmentForUser($user);
        if($assignment instanceof Assignment) {
            return $assignment->getCourse();
        }

        return null;
    }

    public static function setAssignedCourseForUser(\app\users\User $user, ?\app\courses\Course $course): void {
        if(!$course instanceof \app\courses\Course) {
            self::deleteAssignmentForUser($user);
            return;
        }

        $assignment = self::getAssignmentForUser($user);
        if(!$assignment instanceof Assignment) {
            $assignment = new Assignment();
            $assignment->setUserId($user->getId());
        }

        $assignment->setCourseId($course->getId());

        Assignment::dao()->save($assignment);
    }

    public static function deleteAssignmentForUser(\app\users\User $user): void {
        $assignment = self::getAssignmentForUser($user);
        if($assignment instanceof Assignment) {
            self::delete($assignment);
        }
    }

    public static function deleteAssignmentsForCourse(\app\courses\Course $course): void {
        $assignments = self::getAssignmentsForCourse($course);
        foreach($assignments as $assignment) {
            if(!$assignment instanceof Assignment) {
                continue;
            }

            self::delete($assignment);
        }
    }

    public static function delete(Assignment $assignment): void {
        Assignment::dao()->delete($assignment);
    }
}
