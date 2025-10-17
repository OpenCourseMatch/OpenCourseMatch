<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$algorithmRunning = \app\settings\SystemStatus::dao()->get("algorithmRunning") === "true";

\struktal\API\API::sendWrappedJson([
    "running" => $algorithmRunning
]);
