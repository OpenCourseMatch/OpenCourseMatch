<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$algorithmRunning = SystemStatus::dao()->get("algorithmRunning") === "true";

Comm::apiSendJson(HTTPResponses::$RESPONSE_OK, [
    "running" => $algorithmRunning
]);
