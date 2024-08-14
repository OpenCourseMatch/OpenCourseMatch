<?php

$user = Auth::enforceLogin(PermissionLevel::HELPER->value, Router::generate("index"));

echo Blade->run("users.overview");
