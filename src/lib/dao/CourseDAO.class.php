<?php

class CourseDAO extends GenericObjectDAO {
    public function hasId(mixed $id): bool {
        if(!is_numeric($id)) {
            return false;
        }

        $numericId = intval($id);
        return $this->getObject(["id" => $numericId]) instanceof Course;
    }

    public function getChoosableCourses(User $user, array $filter = [], string $orderBy = "id", bool $orderAsc = true, int $limit = -1, int $offset = 0): array {
        $courses = $this->getObjects($filter, $orderBy, $orderAsc, $limit, $offset);
        return array_filter($courses, function($course) use ($user) {
            return $course->canChooseCourse($user);
        });
    }
}
