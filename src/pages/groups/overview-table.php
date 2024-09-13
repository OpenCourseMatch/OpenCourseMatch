<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$groups = Group::dao()->getObjects();

$groups = array_map(function($group) {
    $array = $group->toArray();
    $array["editHref"] = Router::generate("groups-edit", ["group" => $group->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $groups);

Comm::sendJson($groups);
