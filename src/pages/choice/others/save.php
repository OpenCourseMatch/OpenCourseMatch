<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

use \app\users\PermissionLevel;
use \app\settings\SystemSetting;
use \app\choices\Choice;

$get = Validation->create()
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::USER->value
        ], t("The user of which the choice should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("users-overview"));
    });

$account = $get["user"];

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
    ->validate($_POST, function(\struktal\validation\ValidationException $e) use ($account) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
    });

Logger->tag("Choices")->info("User {$user->getId()} ({$user->getFullName()}) is saving / updating the course choices for user {$account->getId()} ({$account->getFullName()}).");

// Create new choices and check if there are duplicates
$chosenCourses = [];
$choices = [];
foreach($post["choice"] as $i => $course) {
    if(in_array($course->getId(), $chosenCourses)) {
        Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) tried to choose course {$course->getId()} ({$course->getTitle()}) for user {$account->getId()} ({$account->getFullName()}) multiple times.");
        InfoMessage->error(t("Each course can only be chosen once."));
        Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
    }

    if(!$course->canChooseCourse($account)) {
        Logger->tag("Choices")->warn("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) tried to choose course {$course->getId()} ({$course->getTitle()}) for user {$account->getId()} ({$account->getFullName()}) but they do not meet the requirements.");
        InfoMessage->error(t("The user of which the choice should be edited does not meet the requirements to participate in at least one of the chosen courses."));
        Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
    }

    Logger->tag("Choices")->trace("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is choosing course {$course->getId()} ({$course->getTitle()}) for user {$account->getId()} ({$account->getFullName()}) with priority {$i}.");

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($account->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Delete old choices from database to prevent collisions
Logger->tag("Choices")->trace("Deleting all old choices for user {$account->getId()} ({$account->getFullName()})");
$oldChoices = Choice::dao()->getObjects([
    "userId" => $account->getId()
]);
foreach($oldChoices as $oldChoice) {
    Choice::dao()->delete($oldChoice);
}

// Save new choices to database
Logger->tag("Choices")->trace("Saving new choices for user {$account->getId()} ({$account->getFullName()}).");
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}
Logger->tag("Choices")->info("User {$user->getId()} ({$user->getFullName()}) has saved / updated the course choices for user {$account->getId()} ({$account->getFullName()}).");

InfoMessage->success(t("The user's chosen courses have been saved."));
Router->redirect(Router->generate("users-edit", ["user" => $account->getId()]));
