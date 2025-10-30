<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => \app\users\PermissionLevel::ADMIN
        ], t("The administrator that should be deleted does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("administrators-overview"));
    });

$account = $get["user"];

\app\users\UserService::delete($account);

Logger->tag("Administrators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the administrator {$account->getId()} ({$account->getFullName()})");

InfoMessage->success(t("The administrator has been deleted."));
Router->redirect(Router->generate("administrators-overview"));
