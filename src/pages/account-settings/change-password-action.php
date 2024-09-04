<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

$validation = new validation\ObjectValidator(true, [
    "current-password" => new validation\StringValidator(true, 8, 256),
    "new-password" => new validation\StringValidator(true, 8, 256),
    "new-password-repeat" => new validation\StringValidator(true, 8, 256),
]);
try {
    $post = $validation->getValidatedValue($_POST);
} catch(validation\ValidationException $e) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
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
