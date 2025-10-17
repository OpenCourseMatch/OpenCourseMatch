<?php

use \app\courses\Course;
use \app\courses\CourseService;
use \app\users\User;
use \app\users\PermissionLevel;
use \app\users\UserService;
use \app\choices\Choice;
use \app\choices\ChoiceService;
use \app\assignments\Assignment;
use \app\assignments\AssignmentService;
use \app\groups\Group;
use \app\groups\GroupService;

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
$coursesAssigned = \app\settings\SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    \struktal\API\API::sendWrappedJson([
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ], \struktal\API\HTTPResponse::METHOD_NOT_ALLOWED);
}

$get = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::USER->value
        ])
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        \struktal\API\API::sendWrappedJson([
            "message" => $e->getMessage()
        ], \struktal\API\HTTPResponse::BAD_REQUEST);
    });

$post = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(false)
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        \struktal\API\API::sendWrappedJson([
            "message" => $e->getMessage()
        ], \struktal\API\HTTPResponse::BAD_REQUEST);
    });

$assignment = AssignmentService::getAssignmentForUser($get["user"]);
if($post["course"] instanceof Course) {
    $course = $post["course"];

    if(!$assignment instanceof Assignment) {
        $assignment = new Assignment();
        $assignment->setUserId($get["user"]->getId());
    }
    $assignment->setCourseId($post["course"]->getId());
    Assignment::dao()->save($assignment);
} else {
    if($assignment instanceof Assignment) {
        Assignment::dao()->delete($assignment);
    }
}

\struktal\API\API::sendWrappedJson([]);
