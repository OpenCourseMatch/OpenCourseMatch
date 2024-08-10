<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

// Clear old session variables
unset($_SESSION["authRecoveryOtpId"]);
unset($_SESSION["authRecoveryOtp"]);

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
    "emailVerified" => true,
    [
        "field" => "oneTimePassword",
        "filterType" => DAOFilterType::NOT_EQUALS,
        "filterValue" => null
    ],
    [
        "field" => "oneTimePasswordExpiration",
        "filterType" => DAOFilterType::GREATER_THAN_EQUALS,
        "filterValue" => DateFormatter::technicalDateTime()
    ]
]);
if(!$user instanceof User) {
    Logger::getLogger("Recovery")->info("Attempted to recover password, but couldn't find user with otpid \"{$otpId}\"");
    new InfoMessage(t("The URL has already been invalidated. Please log in or request a new password recovery email."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}
if(!password_verify($otp, $user->getOneTimePassword())) {
    Logger::getLogger("Recovery")->info("Attempted to recover password, but one-time password does not match");
    new InfoMessage(t("The URL has already been invalidated. Please log in or request a new password recovery email."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}

// Write user details to session
$_SESSION["authRecoveryOtpId"] = $user->getId();
$_SESSION["authRecoveryOtp"] = $otp;

Logger::getLogger("Recovery")->info("Starting password recovery for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");

echo Blade->run("auth.recoveryreset");
