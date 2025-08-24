<?php

$user = Auth->enforceLogin(PermissionLevel::USER->value, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "current-password" => CommonValidators::password(),
        "new-password" => CommonValidators::password(),
        "new-password-repeat" => CommonValidators::password()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("account-settings-change-password"));
}

if(!(preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}/", $post["new-password"]))) {
    new InfoMessage(t("The password does not meet the requirements."), InfoMessageType::ERROR);
    Router->redirect(Router->generate("account-settings-change-password"));
}

if($post["new-password"] != $post["new-password-repeat"]) {
    new InfoMessage(t("The passwords do not match."), InfoMessageType::ERROR);
    Router->redirect(Router->generate("account-settings-change-password"));
}

$tempUser = User::dao()->login($user->getUsername(), false, $post["current-password"]);

if(!($tempUser instanceof User) || $tempUser->getId() !== $user->getId()) {
    new InfoMessage(t("The current password is incorrect."), InfoMessageType::ERROR);
    Router->redirect(Router->generate("account-settings-change-password"));
}

$user->setPassword($post["new-password"]);
User::dao()->save($user);

Logger->tag("ChangePassword")->info("User {$user->getId()} ({$user->getFullName()}) changed their password");

new InfoMessage(t("Your password has been updated."), InfoMessageType::SUCCESS);
Router->redirect(Router->generate("account-settings"));
