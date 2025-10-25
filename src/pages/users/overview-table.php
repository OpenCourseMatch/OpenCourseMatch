<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::FACILITATOR, Router->generate("index"));

$users = \app\users\User::dao()->getObjects([
    "permissionLevel" => \app\users\PermissionLevel::USER
]);

$users = array_map(function(\app\users\User $account) {
    $array = $account->toArray();
    $array["editHref"] = Router->generate("users-edit", ["user" => $account->getId()]);
    $group = $account->getGroup();
    if($group instanceof \app\groups\Group) {
        $array["group"] = $group->getName();
    } else {
        $array["group"] = t("Default group");
    }
    $array["choiceComplete"] = count($account->getSortedChoices()) > 0 && array_reduce($account->getSortedChoices(), function($carry, $choice) {
            return $carry && $choice instanceof \app\choices\Choice && $choice->getCourseId() !== null;
        }, true);
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
