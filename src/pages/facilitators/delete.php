<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "user" => CommonValidators::user(true, [
            "permissionLevel" => PermissionLevel::FACILITATOR->value
        ], t("The facilitator that should be deleted does not exist."))
    ])
    ->build();
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("facilitators-overview"));
}

$account = $get["user"];

$account->preDelete();
User::dao()->delete($account);

Logger->tag("Facilitators")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the facilitator {$account->getId()} ({$account->getFullName()})");

InfoMessage->success(t("The facilitator has been deleted."));
Router->redirect(Router->generate("facilitators-overview"));
