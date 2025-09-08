<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

echo Blade->run("pages.assignment.reset-confirm");
