<?php

$user = Auth->enforceLogin(PermissionLevel::ADMIN->value, Router->generate("index"));

$algorithmRunning = SystemStatus::dao()->get("algorithmRunning") === "true";

\struktal\API\API::sendWrappedJson([
    "running" => $algorithmRunning
]);
