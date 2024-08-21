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

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Groups"),
        "link" => Router::generate("groups-overview")
    ],
    [
        "name" => t(isset($group) ? "Edit group" : "Create group"),
        "link" => Router::generate(isset($group) ? "groups-edit" : "groups-create", isset($group) ? ["groupId" => $group->getId()] : [])
    ]
];

echo Blade->run("groups.edit", [
    "breadcrumbs" => $breadcrumbs,
    "group" => $group ?? null
]);
