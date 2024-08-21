<?php

$user = Auth::getLoggedInUser();
if($user instanceof User) {
    Comm::redirect(Router::generate("dashboard"));
}

echo Blade->run("index");
