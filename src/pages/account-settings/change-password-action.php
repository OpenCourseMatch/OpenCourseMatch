<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

$validation = \validation\ObjectValidator::create(true, [
    "current-password" => \validation\StringValidator::create(true, 8, 256)
        ->setErrorMessage(t("Please fill out all the required fields.")),
    "new-password" => \validation\StringValidator::create(true, 8, 256)
        ->setErrorMessage(t("Please fill out all the required fields.")),
    "new-password-repeat" => \validation\StringValidator::create(true, 8, 256)
        ->setErrorMessage(t("Please fill out all the required fields."))
]);
try {
    $post = $validation->getValidatedValue($_POST);
} catch(validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

if(!(preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}/", $post["new-password"]))) {
    new InfoMessage(t("The password does not meet the requirements."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

if($post["new-password"] != $post["new-password-repeat"]) {
    new InfoMessage(t("The passwords do not match."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

if(User::dao()->login($user->getUsername(), false, $post["current-password"]) != $user) {
    new InfoMessage(t("The current password is incorrect."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

$user->setPassword($post["new-password"]);
User::dao()->save($user);

new InfoMessage(t("Your password has been updated."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("account-settings"));
