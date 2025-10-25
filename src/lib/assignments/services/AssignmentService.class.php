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

    public static function getAssignedCourseForUser(\app\users\User $user): ?\app\courses\Course {
        $assignment = self::getAssignmentForUser($user);
        if($assignment instanceof Assignment) {
            return $assignment->getCourse();
        }

        return null;
    }
}
