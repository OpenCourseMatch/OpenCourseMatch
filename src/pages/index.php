<?php

$user = Auth->getLoggedInUser();
if($user instanceof \app\users\User) {
    Router->redirect(Router->generate("dashboard"));
}

echo Blade->run("pages.index");
