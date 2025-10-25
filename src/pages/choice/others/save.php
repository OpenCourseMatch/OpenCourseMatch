<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$get = Validation->create()
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => \app\users\PermissionLevel::USER
        ], t("The user of which the choice should be edited does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("users-overview"));
    });

$account = $get["user"];

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
    ->validate($_POST, function(\struktal\validation\ValidationException $e) use ($account) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
    });

Logger->tag("Choices")->info("User {$user->getId()} ({$user->getFullName()}) is saving / updating the course choices for user {$account->getId()} ({$account->getFullName()}).");

try {
    \app\choices\ChoiceService::setChoicesForUser($account, $post["choice"]);
} catch(\app\choices\IllegalAmountOfChosenCoursesException $e) {
    InfoMessage->error(t("Please fill out all the required fields."));
    Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
} catch(\app\choices\CourseChosenMultipleTimesException $e) {
    InfoMessage->error(t("Each course can only be chosen once."));
    Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
} catch(\app\choices\CourseRequirementsNotMetException $e) {
    InfoMessage->error(t("The user of which the choice should be edited does not meet the requirements to participate in at least one of the chosen courses."));
    Router->redirect(Router->generate("choice-edit-others", ["user" => $account->getId()]));
}

InfoMessage->success(t("The user's chosen courses have been saved."));
Router->redirect(Router->generate("users-edit", ["user" => $account->getId()]));
