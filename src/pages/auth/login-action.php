<?php

// Check whether the user is already logged in
if(Auth->isLoggedIn()) {
    Router->redirect(Router->generate("index"));
}

// Check whether form fields are given
$validation = Validation->create()
    ->withErrorMessage(t("Please enter your account credentials to log in."))
    ->array()
    ->required()
    ->children([
        "username" => CommonValidators::username(),
        "password" => CommonValidators::password()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Router->redirect(Router->generate("auth-login"));
}

// Check whether there are no users
if(count(User::dao()->getObjects([], "id", true, 1)) === 0) {
    new InfoMessage(t("No users were registered yet. An administrator account has been created."), InfoMessageType::SUCCESS);

    $user = new User();
    $user->setUsername($post["username"]);
    $user->setPassword($post["password"]);
    $user->setEmail($post["username"]);
    $user->setEmailVerified(true);
    $user->setPermissionLevel(PermissionLevel::ADMIN->value);
    $user->setFirstName("Admin");
    $user->setLastName("");
    $user->setGroupId(null);
    $user->setLeadingCourseId(null);
    $user->setLastLogin(null);
    $user->setOneTimePassword(null);
    $user->setOneTimePasswordExpiration(null);
    User::dao()->save($user);

    Logger->tag("Login")->info("An initial administrator account has been created.");
}

$user = User::dao()->login($post["username"], false, $post["password"]);

if($user instanceof \struktal\Auth\LoginError) {
    Logger->tag("Login")->info("User \"{$post["username"]}\" failed to log in: " . $user->name);
    new InfoMessage(t("An account with these credentials does not exist."), InfoMessageType::ERROR);
    Router->redirect(Router->generate("auth-login"));
}

// Reset possibly existing one-time password
$user->setLastLogin(new DateTimeImmutable());
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
User::dao()->save($user);

// Check default values for system settings
SystemSetting::dao()->setDefaults();

Logger->tag("Login")->info("User \"{$post["username"]}\" has logged in (User ID {$user->getId()})");
Auth->login($user);
Router->redirect(Router->generate("index"));
