<?php

// Check whether the user is already logged in
if(Auth::isLoggedIn()) {
    Comm::redirect(Router::generate("index"));
}

$data = [];
if(isset($_SESSION["register-username"])) {
    $data["username"] = $_SESSION["register-username"];
    unset($_SESSION["register-username"]);
}
if(isset($_SESSION["register-email"])) {
    $data["email"] = $_SESSION["register-email"];
    unset($_SESSION["register-email"]);
}

echo Blade->run("auth.register", $data);
