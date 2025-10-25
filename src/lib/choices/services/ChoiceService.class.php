<?php

namespace app\choices;

class ChoiceService {
    public static function getChoicesForUser(\app\users\User $user): array {
        return Choice::dao()->getObjects([
            "userId" => $user->getId()
        ]);
    }

    public static function getChoicesForCourse(\app\courses\Course $course): array {
        return Choice::dao()->getObjects([
            "courseId" => $course->getId()
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

    public static function setChoicesForUser(\app\users\User $user, array $chosenCourses): void {
        $chosenCourseIds = [];
        $choices = [];

        $choiceCount = intval(\app\settings\SystemSetting::dao()->get("choiceCount"));
        if(count($chosenCourses) > $choiceCount || count($chosenCourses) < $choiceCount) {
            Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose an invalid amount of courses (" . count($chosenCourses) . " instead of {$choiceCount}).");
            throw new IllegalAmountOfChosenCoursesException();
        }

        // Loop through chosen courses, validate them before performing database operations
        foreach($chosenCourses as $i => $course) {
            if(!$course instanceof \app\courses\Course) {
                Logger->tag("Choices")->error("Invalid element in chosenCourses array for user {$user->getId()} ({$user->getFullName()}): " . gettype($course) . ".");
                throw new \BadFunctionCallException("All elements in chosenCourses must be instances of \app\courses\Course");
            }

            // Check if the course was already chosen
            if(in_array($course->getId(), $chosenCourseIds)) {
                Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose course {$course->getId()} ({$course->getTitle()}) multiple times.");
                throw new CourseChosenMultipleTimesException();
            }

            // Check if the user is allowed to choose the course
            if(!$course->canChooseCourse($user)) {
                Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose course {$course->getId()} ({$course->getTitle()}) but does not meet the requirements.");
                throw new CourseRequirementsNotMetException();
            }

            Logger->tag("Choices")->trace("User {$user->getId()} ({$user->getFullName()}) is choosing course {$course->getId()} ({$course->getTitle()}) with priority {$i}.");

            $chosenCourseIds[] = $course->getId();
            $choice = new Choice();
            $choice->setUserId($user->getId());
            $choice->setCourseId($course->getId());
            $choice->setPriority($i);
            $choices[] = $choice;
        }

        // Delete old choices from database to prevent collisions
        Logger->tag("Choices")->trace("Deleting all old choices for user {$user->getId()} ({$user->getFullName()})");
        $oldChoices = self::getChoicesForUser($user);
        foreach($oldChoices as $oldChoice) {
            Choice::dao()->delete($oldChoice);
        }

        // Save new choices to database
        Logger->tag("Choices")->trace("Saving new choices for user {$user->getId()} ({$user->getFullName()}).");
        foreach($choices as $choice) {
            Choice::dao()->save($choice);
        }
        Logger->tag("Choices")->info("User {$user->getId()} ({$user->getFullName()}) has saved / updated their course choices.");
    }
}
