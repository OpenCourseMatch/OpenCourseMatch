<?php

namespace app\choices;

use \app\users\User;
use \app\courses\Course;
use \app\settings\SystemSetting;

class ChoiceService {
    public static function delete(Choice $choice): void {
        Choice::dao()->delete($choice);

        // Invalidate cache
        unset(self::$choicesCache[$choice->getUserId()]);
    }

    private static array $choicesCache = [];
    public static function getChoicesOfUser(User $user): array {
        if(!isset(self::$choicesCache[$user->getId()])) {
            $chosenCourses = Choice::dao()->getObjects([ "userId" => $user->getId() ], "priority");
            $choiceCount = intval(SystemSetting::dao()->get("choiceCount"));

            self::$choicesCache[$user->getId()] = [];
            for($i = 0; $i < $choiceCount; $i++) {
                self::$choicesCache[$user->getId()][$i] = null;
            }

            foreach($chosenCourses as $chosenCourse) {
                self::$choicesCache[$user->getId()][$chosenCourse->getPriority()] = $chosenCourse;
            }
        }

        return self::$choicesCache[$user->getId()];
    }

    public static function getChoiceOfUser(User $user, int $priority): ?Choice {
        $chosenCourses = self::getChoicesOfUser($user);
        return $chosenCourses[$priority];
    }

    public static function getCoursePriority(User $user, Course $course): ?int {
        $chosenCourses = self::getChoicesOfUser($user);
        foreach($chosenCourses as $priority => $chosenCourse) {
            if($chosenCourse?->getCourseId() === $course->getId()) {
                return $priority;
            }
        }

        return null;
    }

    public static function getChoicesForCourse(Course $course): array {
        return Choice::dao()->getObjects([ "courseId" => $course->getId() ]);
    }
}
