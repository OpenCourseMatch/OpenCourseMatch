<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    \struktal\API\API::sendWrappedJson([
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ], \struktal\API\HTTPResponse::METHOD_NOT_ALLOWED);
}

$getValidation = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::USER->value
        ])
    ])
    ->build();

$postValidation = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "course" => CommonValidators::course(false)
    ])
    ->build();
try {
    $get = $getValidation->getValidatedValue($_GET);
    $post = $postValidation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    \struktal\API\API::sendWrappedJson([
        "message" => $e->getMessage()
    ], \struktal\API\HTTPResponse::BAD_REQUEST);
}

$assignment = Assignment::dao()->getObject([
    "userId" => $get["user"]->getId()
]);
if($post["course"] instanceof Course) {
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
