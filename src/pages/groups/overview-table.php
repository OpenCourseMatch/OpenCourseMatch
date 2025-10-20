<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$groups = \app\groups\Group::dao()->getObjects();

$groups = array_map(function(\app\groups\Group $group) {
    $array = $group->toArray();
    $array["editHref"] = Router->generate("groups-edit", ["group" => $group->getId()]);
    unset($array["id"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $groups);

\struktal\API\API::sendJson($groups);
