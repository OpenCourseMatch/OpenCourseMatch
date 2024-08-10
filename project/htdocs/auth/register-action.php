<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

function keepPostField(string $postField): void {
    if(isset($_POST[$postField])) {
        $_SESSION["register-" . $postField] = $_POST[$postField];
    }
}

// Check whether form fields are given
if(empty(empty($_POST["username"]) || $_POST["email"]) || empty($_POST["password"]) || empty($_POST["password-repeat"])) {
    keepPostField("username");
    keepPostField("email");

    new InfoMessage(t("Please fill out all the required fields."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}

// Check whether username and email are valid
if(!preg_match("/^(?!.*\.\.)(?!.*\.$)\w[\w.]{2,15}$/", $_POST["username"])) {
    keepPostField("username");
    keepPostField("email");
    new InfoMessage(t("The specified username is invalid. Please follow the required username scheme."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    keepPostField("username");
    keepPostField("email");
    new InfoMessage(t("The specified email address is invalid. Please check for spelling errors and try again."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}

// Check for existing users with the specified username or email
$username = strtolower($_POST["username"]);
$email = strtolower($_POST["email"]);
$existingUsername = User::dao()->getObjects(["username" => $username]);
$existingEmail = User::dao()->getObjects(["email" => $email]);
if(!empty($existingUsername)) {
    if(empty($existingEmail)) {
        keepPostField("email");
    }
    new InfoMessage(t("An account with this username already exists. Please choose another one."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}
if(!empty($existingUsername) || !empty($existingEmail)) {
    if(empty($existingUsername)) {
        keepPostField("username");
    }
    new InfoMessage(t("An account with this email already exists. If that is your account, please log in instead."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}

// Check passwords
if($_POST["password"] !== $_POST["password-repeat"]) {
    keepPostField("username");
    keepPostField("email");
    new InfoMessage(t("The specified passwords do not match. Please check for spelling errors and try again."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}
if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).{8,}$/", $_POST["password"])) {
    keepPostField("username");
    keepPostField("email");
    new InfoMessage(t("The specified password doesn't fulfill the password requirements. Please choose a safer password."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("auth-register"));
}

// Register user
$oneTimePassword = User::dao()->generateOneTimePassword();
$user = User::dao()->register($username, $_POST["password"], $email, 1, $oneTimePassword);

// Send verification email
$otpIdEncoded = urlencode(base64_encode($user->getId()));
$otpEncoded = urlencode($oneTimePassword);
$verificationLink = Router::generate("auth-verify-email", [], true) . "?otpid=" . $otpIdEncoded . "&otp=" . $otpEncoded;
$mail = new Mail();
$mail->setSubject(t("Verify your email address"))
     ->setTextBody(
         t("A new \$\$appName\$\$ account has been registered with this email address.", [
             "appName" => Config::$PROJECT_SETTINGS["PROJECT_NAME"]
         ]) . "\r\n"
         . t("To verify your email address and to complete the registration process, please open the following link:") . "\r\n"
         . $verificationLink . "\r\n"
         . "\r\n"
         . t("If you haven't registered an account at \$\$appName\$\$, you can ignore this email.", [
                "appName" => Config::$PROJECT_SETTINGS["PROJECT_NAME"]
         ])
     )
     ->addRecipient($email)
     ->send();

Logger::getLogger("Register")->info("New user has been registered (\"{$username}\", \"{$email}\")");

Comm::redirect(Router::generate("auth-register-complete"));
