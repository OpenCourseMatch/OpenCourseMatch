<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

$defaultValues = SystemSetting::dao()->defaultValues();

$settingValidators = [];
foreach($defaultValues as $key => $value) {
    $settingValidators[$key] = \validation\Validator::create([
        \validation\IsRequired::create(),
        $value["validation"]
    ]);
}

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create($settingValidators)
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("system-settings"));
}

foreach($defaultValues as $key => $value) {
    SystemSetting::dao()->set($key, strval($post[$key]));
    Logger::getLogger("SystemSettings")->info("User {$user->getId()} ({$user->getFullName()}) changed the system setting \"{$key}\" to \"{$post[$key]}\"");
}

new InfoMessage(t("The system settings have been saved."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("dashboard"));
