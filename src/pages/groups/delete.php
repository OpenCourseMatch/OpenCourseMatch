<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(Group::dao())
        ])
    ])
])->setErrorMessage(t("The group that should be deleted does not exist."));
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("groups-overview"));
}

$group = $get["group"];

Group::dao()->delete($group);
new InfoMessage(t("The group has been deleted."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("groups-overview"));
