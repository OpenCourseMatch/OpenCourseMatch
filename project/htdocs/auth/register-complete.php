<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

echo Blade->run("auth.message", [
    "messages" => [
        t("Your account has been registered."),
        t("Please open the verification link that has been sent to you per email. You can log in to your account once your email address has been verified.")
    ],
    "showLogin" => false
]);
