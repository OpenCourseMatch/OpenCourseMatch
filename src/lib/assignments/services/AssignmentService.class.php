<?php

namespace app\assignments;

use \app\users\User;
use \app\courses\Course;

class AssignmentService {
    public static function delete(Assignment $assignment): void {
        Assignment::dao()->delete($assignment);

        // Invalidate cache
        unset(self::$userAssignmentsCache[$assignment->getUserId()]);
        unset(self::$coursesAssignmentsCache[$assignment->getCourseId()]);
    }

    private static array $userAssignmentsCache = [];
    public static function getAssignmentForUser(User $user): ?Assignment {
        if(!isset(self::$userAssignmentsCache[$user->getId()])) {
            $assignment = Assignment::dao()->getObject([ "userId" => $user->getId() ]);
            if($assignment instanceof Assignment) {
                self::$userAssignmentsCache[$user->getId()] = $assignment;
            } else {
                self::$userAssignmentsCache[$user->getId()] = null;
            }
        }

        return self::$userAssignmentsCache[$user->getId()];
    }

    private static array $coursesAssignmentsCache = [];
    public static function getAssignmentsOfCourse(Course $course): array {
        if(!isset(self::$coursesAssignmentsCache[$course->getId()])) {
            $assignments = Assignment::dao()->getObjects([ "courseId" => $course->getId() ]);
            $users = array_map(function(Assignment $assignment) {
                return $assignment->getUser();
            }, $assignments);

            self::$coursesAssignmentsCache[$course->getId()] = $users;
        }

        return self::$coursesAssignmentsCache[$course->getId()];
    }
}
