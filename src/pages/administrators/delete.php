<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::ADMIN->value
            ])
        ])
    ])
])->setErrorMessage(t("The administrator that should be deleted does not exist."));
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("administrators-overview"));
}

$account = $get["user"];

$account->preDelete();
User::dao()->delete($account);

Logger::getLogger("Administrators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the administrator {$account->getId()} ({$account->getFullName()})");

new InfoMessage(t("The administrator has been deleted."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("administrators-overview"));
