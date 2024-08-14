<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

if(isset($_GET["groupId"]) && is_numeric($_GET["groupId"])) {
    $groupId = intval($_GET["groupId"]);
    $group = Group::dao()->getObject(["id" => $groupId]);
    if(!$group instanceof Group) {
        new InfoMessage(t("The group that should be edited does not exist."), InfoMessageType::ERROR);
        Comm::redirect(Router::generate("groups-overview"));
    }
}

echo Blade->run("groups.edit", ["group" => $group ?? null]);
