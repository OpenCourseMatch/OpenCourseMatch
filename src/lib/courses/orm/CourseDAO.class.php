<?php

namespace app\courses;

class CourseDAO extends \struktal\ORM\GenericEntityDAO {
    public function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return $this->getObject(["id" => $numericId]) instanceof Course;
    }

    public function getChoosableCourses(\app\users\User $user, array $filter = [], string $orderBy = "id", bool $orderAsc = true, int $limit = -1, int $offset = 0): array {
        $courses = $this->getObjects($filter, $orderBy, $orderAsc, $limit, $offset);
        return array_filter($courses, function($course) use ($user) {
            return $course->canChooseCourse($user);
        });
    }
}
