<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

// Check whether a one-time password has been specified
if(empty($_GET["otpid"]) || empty($_GET["otp"])) {
    new InfoMessage(t("An error has occurred. Please try again later."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}

$otpId = base64_decode(urldecode($_GET["otpid"]));
$otp = urldecode($_GET["otp"]);

// Find the user from the one-time password
$user = User::dao()->getObject([
    "id" => $otpId,
    "emailVerified" => false,
    [
        "field" => "oneTimePassword",
        "filterType" => DAOFilterType::NOT_EQUALS,
        "filterValue" => null
    ],
    "oneTimePasswordExpiration" => null
]);
if(!$user instanceof User) {
    Logger::getLogger("Email-Verification")->info("Attempted to verify an email, but couldn't find user with otpid \"{$otpId}\"");
    new InfoMessage(t("The URL has already been invalidated. Please log in or request a new password recovery email."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}
if(!password_verify($otp, $user->getOneTimePassword())) {
    Logger::getLogger("Email-Verification")->info("Attempted to verify an email, but one-time password does not match");
    new InfoMessage(t("The URL has already been invalidated. Please log in or request a new password recovery email."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}

// Update the user object in the database
$user->setEmailVerified(true);
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
$user->setUpdated(new DateTime());
User::dao()->save($user);

Logger::getLogger("Email-Verification")->info("The email address \"{$user->getEmail()}\" (User ID \"{$user->getId()}\") has been verified");

Comm::redirect(Router::generate("auth-verify-email-complete"));
