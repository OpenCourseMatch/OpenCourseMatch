<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

// Check whether a one-time password has been specified
if(empty($_SESSION["authRecoveryOtpId"]) || empty($_SESSION["authRecoveryOtp"])) {
    new InfoMessage(t("An error has occurred. Please try again later."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-login"));
}

$otpId = $_SESSION["authRecoveryOtpId"];
$otp = $_SESSION["authRecoveryOtp"];

// Clear old session variables
unset($_SESSION["authRecoveryOtpId"]);
unset($_SESSION["authRecoveryOtp"]);

// Generate redirect link for error cases
$otpIdEncoded = urlencode(base64_encode($otpId));
$otpEncoded = urlencode($otp);
$resetLink = Router::generate("auth-recovery-reset") . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;

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

// Check whether form fields are given
if(empty($_POST["password"]) || empty($_POST["password-repeat"])) {
    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    Comm::redirect($resetLink);
}

// Check passwords
if($_POST["password"] !== $_POST["password-repeat"]) {
    new InfoMessage(t("The specified passwords do not match. Please check for spelling errors and try again."), InfoMessageType::ERROR);
    Comm::redirect($resetLink);
}
if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}$/", $_POST["password"])) {
    new InfoMessage(t("The specified password doesn't fulfill the password requirements. Please choose a safer password."), InfoMessageType::ERROR);
    Comm::redirect($resetLink);
}

// Change password
$user->setPassword($_POST["password"]);
$user->setOneTimePassword(null);
$user->setOneTimePasswordExpiration(null);
User::dao()->save($user);

Logger::getLogger("Recovery")->info("Changed password for user with email \"{$user->getEmail()}\" (User ID \"{$user->getId()}\")");

Comm::redirect(Router::generate("auth-recovery-reset-complete"));
