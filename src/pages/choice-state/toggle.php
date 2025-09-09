<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$userActionsAllowed = SystemStatus::dao()->get("userActionsAllowed") === "true";

echo Blade->run("pages.choice-state.toggle", [
    "userActionsAllowed" => $userActionsAllowed
]);
