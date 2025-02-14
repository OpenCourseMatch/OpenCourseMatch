<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));
$coursesAssigned = SystemStatus::dao()->get("coursesAssigned") === "true";

if(!$coursesAssigned) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_METHOD_NOT_ALLOWED, [
        "message" => t("An error has occurred whilst attempting to edit the course assignment. Please try again later.")
    ]);
}

$getValidation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value
            ])
        ])
    ])
])->setErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."));

$postValidation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "course" => \validation\Validator::create([
            \validation\IsInDatabase::create(Course::dao())
        ])
    ])
])->setErrorMessage(t("An error has occurred whilst attempting to edit the course assignment. Please try again later."));
try {
    $get = $getValidation->getValidatedValue($_GET);
    $post = $postValidation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    Comm::apiSendJson(HTTPResponses::$RESPONSE_BAD_REQUEST, [
        "message" => $e->getMessage()
    ]);
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

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, []);
