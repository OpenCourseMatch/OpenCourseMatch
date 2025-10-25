<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

if($user->getPermissionLevel()->value > \app\users\PermissionLevel::USER->value) {
    InfoMessage->error(t("Choosing courses is only available to participants and tutors."));
    Router->redirect(Router->generate("index"));
}

if(\app\settings\SystemStatus::dao()->get("userActionsAllowed") !== "true") {
    InfoMessage->error(t("The course selection has already been disabled. You can no longer update your course preferences."));
    Router->redirect(Router->generate("index"));
}

$choiceCount = intval(\app\settings\SystemSetting::dao()->get("choiceCount"));

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

try {
    \app\choices\ChoiceService::setChoicesForUser($user, $post["choice"]);
} catch(\app\choices\IllegalAmountOfChosenCoursesException $e) {
    InfoMessage->error(t("Please fill out all the required fields."));
    Router->redirect(Router->generate("choice-edit"));
} catch(\app\choices\CourseChosenMultipleTimesException $e) {
    InfoMessage->error(t("Each course can only be chosen once."));
    Router->redirect(Router->generate("choice-edit"));
} catch(\app\choices\CourseRequirementsNotMetException $e) {
    InfoMessage->error(t("You do not meet the requirements to participate in at least one of your chosen courses."));
    Router->redirect(Router->generate("choice-edit"));
}

InfoMessage->success(t("Your chosen courses have been saved."));
Router->redirect(Router->generate("dashboard"));
