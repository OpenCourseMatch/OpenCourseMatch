<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$users = User::dao()->getObjects([
    "permissionLevel" => PermissionLevel::ADMIN->value
]);

$users = array_map(function(User $account) {
    $array = $account->toArray();
    $array["editHref"] = Router::generate("administrators-edit", ["user" => $account->getId()]);
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
