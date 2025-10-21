<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$defaultValues = \app\settings\SystemSetting::dao()->defaultValues();

$settingValidators = [];
foreach($defaultValues as $key => $value) {
    $settingValidators[$key] = $value["validation"];
}

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children($settingValidators)
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("system-settings"));
}

foreach($defaultValues as $key => $value) {
    \app\settings\SystemSetting::dao()->set($key, strval($post[$key]));
    Logger->tag("SystemSettings")->info("User {$user->getId()} ({$user->getFullName()}) changed the system setting \"{$key}\" to \"{$post[$key]}\"");
}

InfoMessage->success(t("The system settings have been saved."));
Router->redirect(Router->generate("dashboard"));
