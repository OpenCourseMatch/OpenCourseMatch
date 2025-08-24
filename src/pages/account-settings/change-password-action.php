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
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("account-settings-change-password"));
}

if(!(preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}/", $post["new-password"]))) {
    InfoMessage->error(t("The password does not meet the requirements."));
    Router->redirect(Router->generate("account-settings-change-password"));
}

if($post["new-password"] != $post["new-password-repeat"]) {
    InfoMessage->error(t("The passwords do not match."));
    Router->redirect(Router->generate("account-settings-change-password"));
}

$tempUser = User::dao()->login($user->getUsername(), false, $post["current-password"]);

if(!($tempUser instanceof User) || $tempUser->getId() !== $user->getId()) {
    InfoMessage->error(t("The current password is incorrect."));
    Router->redirect(Router->generate("account-settings-change-password"));
}

$user->setPassword($post["new-password"]);
User::dao()->save($user);

Logger->tag("ChangePassword")->info("User {$user->getId()} ({$user->getFullName()}) changed their password");

InfoMessage->success(t("Your password has been updated."));
Router->redirect(Router->generate("account-settings"));
