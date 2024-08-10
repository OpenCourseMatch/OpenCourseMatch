<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

// Check whether form fields are given
if(empty($_POST["email"])) {
    new InfoMessage(t("Please enter your accounts verified email address."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-recovery-request"));
}

// Check whether the email is valid
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    new InfoMessage(t("The specified email address is invalid. Please check for spelling errors and try again."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-recovery-request"));
}

$email = strtolower($_POST["email"]);

$user = User::dao()->getObject([
    "email" => $email,
    "emailVerified" => true
]);

if(!$user instanceof GenericUser) {
    Logger::getLogger("Recovery")->info("Failed to request password recovery for email \"{$_POST["email"]}\"");
    new InfoMessage(t("An account with this email could not be found. Please check for spelling errors and try again."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-recovery-request"));
}

// Send password recovery mail
$oneTimePassword = User::dao()->generateOneTimePassword();
$oneTimePasswordExpiration = DateFormatter::technicalDateTime((new DateTime())->modify("+15 minutes"));

$user->setOneTimePassword($oneTimePassword);
$user->setOneTimePasswordExpiration($oneTimePasswordExpiration);
User::dao()->save($user);

$otpIdEncoded = urlencode(base64_encode($user->getId()));
$otpEncoded = urlencode($oneTimePassword);
$verificationLink = Router::generate("auth-recovery-reset", [], true) . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;
$mail = new Mail();
$mail->setSubject(t("Password recovery"))
    ->setTextBody(
        t("You have requested to recover your password for your \$\$appName\$\$ account.", [
            "appName" => Config::$PROJECT_SETTINGS["PROJECT_NAME"]
        ]) . "\r\n"
        . t("To set a new password, please open the following link:") . "\r\n"
        . $verificationLink . "\r\n"
        . t("This link is valid for 15 minutes.") . "\r\n"
        . "\r\n"
        . t("If you haven't requested a password recovery for your \$\$appName\$\$ account, you can ignore this email.", [
            "appName" => Config::$PROJECT_SETTINGS["PROJECT_NAME"]
        ])
    )
    ->addRecipient($email)
    ->send();

Logger::getLogger("Recovery")->info("Requested password recovery for user with email \"{$_POST["email"]}\" (User ID {$user->getId()})");
Comm::redirect(Router::generate("auth-recovery-request-complete"));
