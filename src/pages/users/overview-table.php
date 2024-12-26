<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$users = User::dao()->getObjects([
    "permissionLevel" => PermissionLevel::USER->value
]);

$users = array_map(function(User $account) {
    $array = $account->toArray();
    $array["editHref"] = Router::generate("users-edit", ["user" => $account->getId()]);
    $group = $account->getGroup();
    if($group instanceof Group) {
        $array["group"] = $group->getName();
    } else {
        $array["group"] = t("Default group");
    }
    unset($array["id"]);
    unset($array["password"]);
    unset($array["email"]);
    unset($array["emailVerified"]);
    unset($array["permissionLevel"]);
    unset($array["oneTimePassword"]);
    unset($array["oneTimePasswordExpiration"]);
    unset($array["created"]);
    unset($array["updated"]);
    return $array;
}, $users);

Comm::sendJson($users);
