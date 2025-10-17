<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$users = \app\users\User::dao()->getObjects([
    "permissionLevel" => \app\users\PermissionLevel::ADMIN->value
]);

$users = array_map(function(\app\users\User $account) {
    $array = $account->toArray();
    $array["editHref"] = Router->generate("administrators-edit", ["user" => $account->getId()]);
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

\struktal\API\API::sendJson($users);
