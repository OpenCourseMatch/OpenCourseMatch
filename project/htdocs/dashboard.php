<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

echo Blade->run("dashboard");
