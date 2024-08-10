<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

echo Blade->run("auth.message", [
    "messages" => [
        t("Your password has been changed."),
        t("You can now log in to your account.")
    ],
    "showLogin" => true
]);
