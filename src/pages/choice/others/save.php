<?php

$user = Auth->enforceLogin(PermissionLevel::FACILITATOR->value, Router->generate("index"));

$getValidation = Validation->create()
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::USER->value
        ], t("The user of which the choice should be edited does not exist."))
    ])
    ->build();
try {
    $get = $getValidation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("users-overview"));
}

$account = $get["user"];

$choiceCount = intval(SystemSetting::dao()->get("choiceCount"));

$choiceValidation = [];
for($i = 0; $i < $choiceCount; $i++) {
    $choiceValidation[$i] = CommonValidators::course();
}

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "choice" => Validation->create()
            ->array()
            ->minLength($choiceCount)
            ->maxLength($choiceCount)
            ->children($choiceValidation)
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
}

Logger::getLogger("Choices")->info("User {$user->getId()} ({$user->getFullName()}) is saving / updating the course choices for user {$account->getId()} ({$account->getFullName()}).");

// Create new choices and check if there are duplicates
$chosenCourses = [];
$choices = [];
foreach($post["choice"] as $i => $course) {
    if(in_array($course->getId(), $chosenCourses)) {
        Logger::getLogger("Choices")->warn("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) tried to choose course {$course->getId()} ({$course->getTitle()}) for user {$account->getId()} ({$account->getFullName()}) multiple times.");
        new InfoMessage(t("Each course can only be chosen once."), InfoMessageType::ERROR);
        Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
    }

    if(!$course->canChooseCourse($account)) {
        Logger::getLogger("Choices")->warn("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) tried to choose course {$course->getId()} ({$course->getTitle()}) for user {$account->getId()} ({$account->getFullName()}) but they do not meet the requirements.");
        new InfoMessage(t("The user of which the choice should be edited does not meet the requirements to participate in at least one of the chosen courses."), InfoMessageType::ERROR);
        Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
    }

    Logger::getLogger("Choices")->trace("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is choosing course {$course->getId()} ({$course->getTitle()}) for user {$account->getId()} ({$account->getFullName()}) with priority {$i}.");

    $chosenCourses[] = $course->getId();
    $choice = new Choice();
    $choice->setUserId($account->getId());
    $choice->setCourseId($course->getId());
    $choice->setPriority($i);
    $choices[] = $choice;
}

// Delete old choices from database to prevent collisions
Logger::getLogger("Choices")->trace("Deleting all old choices for user {$account->getId()} ({$account->getFullName()})");
$oldChoices = Choice::dao()->getObjects([
    "userId" => $account->getId()
]);
foreach($oldChoices as $oldChoice) {
    Choice::dao()->delete($oldChoice);
}

// Save new choices to database
Logger::getLogger("Choices")->trace("Saving new choices for user {$account->getId()} ({$account->getFullName()}).");
foreach($choices as $choice) {
    Choice::dao()->save($choice);
}
Logger::getLogger("Choices")->info("User {$user->getId()} ({$user->getFullName()}) has saved / updated the course choices for user {$account->getId()} ({$account->getFullName()}).");

new InfoMessage(t("The user's chosen courses have been saved."), InfoMessageType::SUCCESS);
Router->redirect(Router->generate("users-edit", ["user" => $account->getId()]));
