<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_METHOD_NOT_ALLOWED, [
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ]);
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
    Comm::apiSendJson(HTTPResponses::$RESPONSE_BAD_REQUEST, [
        "message" => $e->getMessage()
    ]);
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

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, []);
