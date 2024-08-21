<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

// Check whether form fields are given
if(empty($_POST["username"]) || empty($_POST["password"])) {
    new InfoMessage(t("Please enter your account credentials to log in."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}

// Check whether there are no users
if(count(User::dao()->getObjects([], "id", true, 1)) === 0) {
    new InfoMessage(t("No users were registered yet. An administrator account has been created."), InfoMessageType::SUCCESS);

    $user = new User();
    $user->setUsername($_POST["username"]);
    $user->setPassword($_POST["password"]);
    $user->setEmail($_POST["username"]);
    $user->setEmailVerified(true);
    $user->setPermissionLevel(PermissionLevel::ADMIN->value);
    $user->setFirstName("Admin");
    $user->setLastName("");
    $user->setGroup(null);
    $user->setLeadingCourse(null);
    $user->setLastLogin(null);
    $user->setOneTimePassword(null);
    $user->setOneTimePasswordExpiration(null);
    User::dao()->save($user);
}

$user = User::dao()->login($_POST["username"], false, $_POST["password"]);

if(!$user instanceof GenericUser) {
    Logger::getLogger("Login")->info("User \"{$_POST["username"]}\" failed to log in: " . ($user === 0 ? "User not found" : ($user === 1 ? "Password incorrect" : "Email not verified")));
    new InfoMessage(t("An account with these credentials does not exist."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}

// Reset possibly existing one-time password
$user->setLastLogin(DateFormatter::technicalDateTime());
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
User::dao()->save($user);

Logger::getLogger("Login")->info("User \"{$_POST["username"]}\" has logged in (User ID {$user->getId()})");
Auth::login($user);
Comm::redirect(Router::generate("index"));
