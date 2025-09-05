<?php

$user = Auth->getLoggedInUser();
if($user instanceof User) {
    Router->redirect(Router->generate("dashboard"));
}

echo Blade->run("pages.index");
