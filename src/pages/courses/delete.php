<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "course" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(Course::dao())
        ])
    ])
])->setErrorMessage(t("The course that should be deleted does not exist."));
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("courses-overview"));
}

$course = $get["course"];

Course::dao()->delete($course);
new InfoMessage(t("The course has been deleted."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("courses-overview"));
