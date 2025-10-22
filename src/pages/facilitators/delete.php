<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$get = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => \app\users\PermissionLevel::FACILITATOR
        ], t("The facilitator that should be deleted does not exist."))
    ])
    ->validate($_GET, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("facilitators-overview"));
    });

$account = $get["user"];

$account->preDelete();
\app\users\User::dao()->delete($account);

Logger->tag("Facilitators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the facilitator {$account->getId()} ({$account->getFullName()})");

InfoMessage->success(t("The facilitator has been deleted."));
Router->redirect(Router->generate("facilitators-overview"));
