<?php

$user = Auth::enforceLogin(PermissionLevel::HELPER->value, Router::generate("index"));

$users = User::dao()->getObjects([
    "permissionLevel" => PermissionLevel::USER->value
]);

$users = array_map(function($account) {
    $array = $account->toArray();
    $array["editHref"] = Router::generate("users-edit", ["userId" => $account->getId()]);
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
