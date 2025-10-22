<?php

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
            "permissionLevel" => \app\users\PermissionLevel::USER
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

$assignment = \app\assignments\Assignment::dao()->getObject([
    "userId" => $get["user"]->getId()
]);
if($post["course"] instanceof \app\courses\Course) {
    if(!$assignment instanceof \app\assignments\Assignment) {
        $assignment = new \app\assignments\Assignment();
        $assignment->setUserId($get["user"]->getId());
    }
    $assignment->setCourseId($post["course"]->getId());
    \app\assignments\Assignment::dao()->save($assignment);
} else {
    if($assignment instanceof \app\assignments\Assignment) {
        \app\assignments\Assignment::dao()->delete($assignment);
    }
}

\struktal\API\API::sendWrappedJson([]);
