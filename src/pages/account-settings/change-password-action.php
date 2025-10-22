<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "current-password" => CommonValidators::password(),
        "new-password" => CommonValidators::password(),
        "new-password-repeat" => CommonValidators::password()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("account-settings-change-password"));
    });

// Check old password
try {
    $tempUser = \app\users\UserService::login($user->getUsername(), false, $post["current-password"]);
} catch(Exception $e) {
    InfoMessage->error(t("The current password is incorrect."));
    Router->redirect(Router->generate("account-settings-change-password"));
}

// Check new password
try {
    \app\users\Validations::checkTwoPasswords($post["new-password"], $post["new-password-repeat"]);
} catch(\app\users\PasswordMismatchException $e) {
    InfoMessage->error(t("The passwords do not match."));
    Router->redirect(Router->generate("account-settings-change-password"));
} catch(\app\users\WeakPasswordException $e) {
    InfoMessage->error(t("The password does not meet the requirements."));
    Router->redirect(Router->generate("account-settings-change-password"));
}

\app\users\UserService::changePassword($user, $post["new-password"]);

InfoMessage->success(t("Your password has been updated."));
Router->redirect(Router->generate("account-settings"));
