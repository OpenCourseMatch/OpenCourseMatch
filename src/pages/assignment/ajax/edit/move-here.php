<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));
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
        "course" => CommonValidators::course()
    ])
    ->build();

$postValidation = Validation->create()
    ->withErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::USER->value
        ])
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
    "userId" => $post["user"]->getId()
]);
if(!$assignment instanceof Assignment) {
    $assignment = new Assignment();
    $assignment->setUserId($post["user"]->getId());
}
$assignment->setCourseId($get["course"]->getId());
Assignment::dao()->save($assignment);

\struktal\API\API::sendWrappedJson([]);
