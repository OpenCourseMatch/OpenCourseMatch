<?php

namespace app\choices;

class ChoiceService {
    public static function getChoicesForUser(\app\users\User $user): array {
        return Choice::dao()->getObjects([
            "userId" => $user->getId()
        ]);
    }

    public static function getSortedChoicesForUser(\app\users\User $user): array {
        $choiceObjects = self::getChoicesForUser($user);
        $choiceCount = intval(\app\settings\SystemSetting::dao()->get("choiceCount"));

        $choices = [];
        for($i = 0; $i < $choiceCount; $i++) {
            $choices[$i] = null;
        }

        foreach($choiceObjects as $choice) {
            $choices[$choice->getPriority()] = $choice;
        }

        return $choices;
    }

    public static function getChoiceWithPriorityForUser(\app\users\User $user, int $priority): ?Choice {
        $choice = Choice::dao()->getObject([
            "userId" => $user->getId(),
            "priority" => $priority
        ]);

        if($choice instanceof Choice) {
            return $choice;
        }

        return null;
    }

    public static function getCoursePriorityForUser(\app\users\User $user, \app\courses\Course $course): ?int {
        $choice = Choice::dao()->getObject([
            "userId" => $user->getId(),
            "courseId" => $course->getId()
        ]);

        if($choice instanceof Choice) {
            return $choice->getPriority();
        }

        return null;
    }
}
