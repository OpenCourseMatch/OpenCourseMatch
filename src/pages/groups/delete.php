<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "group" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(Group::dao())
        ])->setErrorMessage(t("The group that should be deleted does not exist."))
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("groups-overview"));
}

$group = $get["group"];

$group->preDelete();
Group::dao()->delete($group);

Logger::getLogger("Groups")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the group {$group->getId()} ({$group->getName()})");

new InfoMessage(t("The group has been deleted."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("groups-overview"));
