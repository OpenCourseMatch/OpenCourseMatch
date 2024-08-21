<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

if(empty($_POST["current-password"]) || empty($_POST["new-password"]) || empty($_POST["new-password-repeat"])) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

if(!(preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}/", $_POST["new-password"]))) {
    new InfoMessage(t("The password does not meet the requirements."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

if($_POST["new-password"] != $_POST["new-password-repeat"]) {
    new InfoMessage(t("The passwords do not match."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

if(User::dao()->login($user->getUsername(), false, $_POST["current-password"]) != $user) {
    new InfoMessage(t("The current password is incorrect."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("account-settings-change-password"));
}

$user->setPassword($_POST["new-password"]);
User::dao()->save($user);

new InfoMessage(t("Your password has been updated."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("account-settings"));
