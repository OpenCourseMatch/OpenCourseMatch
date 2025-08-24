<?php

$user = Auth->enforceLogin(PermissionLevel::FACILITATOR->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(false, [], t("The course that should be edited does not exist.")),
        "title" => Validation->create()
            ->string()
            ->maxLength(256)
            ->build(),
        "organizer" => Validation->create()
            ->string(false)
            ->maxLength(256)
            ->build(),
        "minClearance" => Validation->create()
            ->int()
            ->build(),
        "maxClearance" => Validation->create()
            ->int(false)
            ->build(),
        "minParticipants" => Validation->create()
            ->int()
            ->minValue(0)
            ->build(),
        "maxParticipants" => Validation->create()
            ->int()
            ->minValue(1)
            ->build()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    if(isset($_POST["course"]) && !Course::dao()->hasId($_POST["course"])) {
        Router->redirect(Router->generate("courses-overview"));
    } else if(isset($_POST["course"])) {
        Router->redirect(Router->generate("courses-edit", ["course" => $_POST["course"]]));
    } else {
        Router->redirect(Router->generate("courses-create"));
    }
}

if(isset($post["maxClearance"]) && $post["minClearance"] > $post["maxClearance"]) {
    InfoMessage->error(t("The minimum clearance level must be lower than the maximum clearance level."));
    if(isset($post["course"])) {
        Router->redirect(Router->generate("courses-edit", ["course" => $post["course"]->getId()]));
    } else {
        Router->redirect(Router->generate("courses-create"));
    }
}

if(isset($post["maxParticipants"]) && $post["minParticipants"] > $post["maxParticipants"]) {
    InfoMessage->error(t("The minimum number of participants must be lower than the maximum number of participants."));
    if(isset($post["course"])) {
        Router->redirect(Router->generate("courses-edit", ["course" => $post["course"]->getId()]));
    } else {
        Router->redirect(Router->generate("courses-create"));
    }
}

$course = new Course();
if(isset($post["course"])) {
    $course = $post["course"];
}

$course->setTitle($post["title"]);
$course->setOrganizer($post["organizer"]);
$course->setMinClearance($post["minClearance"]);
$course->setMaxClearance($post["maxClearance"]);
$course->setMinParticipants($post["minParticipants"]);
$course->setMaxParticipants($post["maxParticipants"]);
Course::dao()->save($course);

Logger->tag("Courses")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) saved the course {$course->getId()} ({$course->getTitle()})");

InfoMessage->success(t("The course has been saved."));
Router->redirect(Router->generate("courses-overview"));
