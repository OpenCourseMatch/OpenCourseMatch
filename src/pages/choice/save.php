<?php

use \app\users\PermissionLevel;
use \app\settings\SystemStatus;
use \app\settings\SystemSetting;
use \app\choices\Choice;

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

if($user->getPermissionLevel() > PermissionLevel::USER->value) {
    InfoMessage->error(t("Choosing courses is only available to participants and tutors."));
    Router->redirect(Router->generate("index"));
}

if(SystemStatus::dao()->get("userActionsAllowed") !== "true") {
    InfoMessage->error(t("The course selection has already been disabled. You can no longer update your course preferences."));
    Router->redirect(Router->generate("index"));
}

$choiceCount = intval(SystemSetting::dao()->get("choiceCount"));

$choiceValidation = [];
for($i = 0; $i < $choiceCount; $i++) {
    $choiceValidation[$i] = CommonValidators::course();
}

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "choice" => Validation->create()
            ->array()
            ->minLength($choiceCount)
            ->maxLength($choiceCount)
            ->children($choiceValidation)
            ->build()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("choice-edit"));
    });

Logger->tag("Choices")->info("User {$user->getId()} ({$user->getFullName()}) is saving / updating their course choices.");

// Create new choices and check if there are duplicates
$chosenCourses = [];
$choices = [];
foreach($post["choice"] as $i => $course) {
    if(in_array($course->getId(), $chosenCourses)) {
        Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose course {$course->getId()} ({$course->getTitle()}) multiple times.");
        InfoMessage->error(t("Each course can only be chosen once."));
        Router->redirect(Router->generate("choice-edit"));
    }

    if(!$course->canChooseCourse($user)) {
        Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}) tried to choose course {$course->getId()} ({$course->getTitle()}) but does not meet the requirements.");
        InfoMessage->error(t("You do not meet the requirements to participate in at least one of your chosen courses."));
        Router->redirect(Router->generate("choice-edit"));
    }

    Logger->tag("Choices")->trace("User {$user->getId()} ({$user->getFullName()}) is choosing course {$course->getId()} ({$course->getTitle()}) with priority {$i}.");

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($user->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Delete old choices from database to prevent collisions
Logger->tag("Choices")->trace("Deleting all old choices for user {$user->getId()} ({$user->getFullName()})");
$oldChoices = Choice::dao()->getObjects([
    "userId" => $user->getId()
]);
foreach($oldChoices as $oldChoice) {
    Choice::dao()->delete($oldChoice);
}

// Save new choices to database
Logger->tag("Choices")->trace("Saving new choices for user {$user->getId()} ({$user->getFullName()}).");
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}
Logger->tag("Choices")->info("User {$user->getId()} ({$user->getFullName()}) has saved / updated their course choices.");

InfoMessage->success(t("Your chosen courses have been saved."));
Router->redirect(Router->generate("dashboard"));
