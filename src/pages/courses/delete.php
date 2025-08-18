<?php

$user = Auth->enforceLogin(PermissionLevel::FACILITATOR->value, Router->generate("index"));

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
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("courses-overview"));
}

$course = $get["course"];

$course->preDelete();
Course::dao()->delete($course);

Logger::getLogger("Courses")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the course {$course->getId()} ({$course->getTitle()})");

new InfoMessage(t("The course has been deleted."), InfoMessageType::SUCCESS);
Router->redirect(Router->generate("courses-overview"));
