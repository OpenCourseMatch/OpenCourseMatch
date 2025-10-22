<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether form fields are given
$post = Validation->create()
    ->withErrorMessage(t("Please log in with your account's credentials."))
    ->array()
    ->required()
    ->children([
        "username" => \app\users\Validations::username(),
        "password" => \app\users\Validations::password()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("auth-login"));
    });

// Check whether there are no users
if(!\app\users\UserService::userExists(null, null)) {
    try {
        \app\users\Validations::checkPassword($post["password"]);
    } catch(\app\users\WeakPasswordException $e) {
        InfoMessage->error(t("The password does not meet the requirements."));
        Router->redirect(Router->generate("auth-login"));
    }

    \app\users\UserService::register(
        $post["username"],
        $post["password"],
        \app\users\PermissionLevel::ADMIN,
        "Admin",
        "",
        null,
        null
    );

    InfoMessage->success(t("No users were registered yet. An administrator account has been created."));
    Logger->tag("Login")->info("An initial administrator account has been created.");
}

try {
    $user = \app\users\UserService::login($post["username"], false, $post["password"]);
} catch(\app\users\UserNotFoundException | \app\users\InvalidPasswordException | \app\users\EmailNotVerifiedException $e) {
    InfoMessage->error(t("An account with these credentials does not exist."));
    Router->redirect(Router->generate("auth-login"));
}

// Check default values for system settings
\app\settings\SystemSetting::dao()->setDefaults();

Logger->tag("Login")->info("User \"{$post["username"]}\" has logged in (User ID {$user->getId()})");
Auth->sessionLogin($user);
Router->redirect(Router->generate("index"));
