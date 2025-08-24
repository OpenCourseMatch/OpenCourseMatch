<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::ADMIN->value
        ], t("The administrator that should be deleted does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("administrators-overview"));
}

$account = $get["user"];

$account->preDelete();
User::dao()->delete($account);

Logger->tag("Administrators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the administrator {$account->getId()} ({$account->getFullName()})");

new InfoMessage(t("The administrator has been deleted."), InfoMessageType::SUCCESS);
Router->redirect(Router->generate("administrators-overview"));
