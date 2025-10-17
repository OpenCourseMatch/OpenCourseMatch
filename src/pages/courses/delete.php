<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(true, [], t("The course that should be deleted does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("courses-overview"));
}

$course = $get["course"];

$course->preDelete();
Course::dao()->delete($course);

Logger->tag("Courses")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the course {$course->getId()} ({$course->getTitle()})");

InfoMessage->success(t("The course has been deleted."));
Router->redirect(Router->generate("courses-overview"));
