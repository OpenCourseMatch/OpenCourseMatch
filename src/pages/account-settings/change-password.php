<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

echo Blade->run("pages.accountsettings.changepassword");
