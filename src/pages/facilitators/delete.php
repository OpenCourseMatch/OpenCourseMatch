<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsRequired::create(),
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::FACILITATOR->value
            ])
        ])
    ])
])->setErrorMessage(t("The facilitator that should be deleted does not exist."));
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("facilitators-overview"));
}

$account = $get["user"];

User::dao()->delete($account);
new InfoMessage(t("The facilitator has been deleted."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("facilitators-overview"));
