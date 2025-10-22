<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => \app\users\PermissionLevel::USER
        ], t("The user that should be deleted does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("users-overview"));
    });

$account = $get["user"];

$account->preDelete();
\app\users\User::dao()->delete($account);

Logger->tag("Users")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");

InfoMessage->success(t("The user has been deleted."));
Router->redirect(Router->generate("users-overview"));
