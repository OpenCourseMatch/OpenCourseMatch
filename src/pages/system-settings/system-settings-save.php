<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$defaultValues = SystemSetting::dao()->defaultValues();

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
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("system-settings"));
}

foreach($defaultValues as $key => $value) {
    SystemSetting::dao()->set($key, strval($post[$key]));
    Logger->tag("SystemSettings")->info("User {$user->getId()} ({$user->getFullName()}) changed the system setting \"{$key}\" to \"{$post[$key]}\"");
}

new InfoMessage(t("The system settings have been saved."), InfoMessageType::SUCCESS);
Router->redirect(Router->generate("dashboard"));
