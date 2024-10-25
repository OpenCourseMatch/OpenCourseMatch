<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

if($user->getPermissionLevel() > PermissionLevel::USER->value) {
    new InfoMessage(t("Choosing courses is only available to participants and tutors."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

$choosableCourses = Course::dao()->getChoosableCourses($user);
$voteCount = intval(SystemSetting::dao()->get("voteCount"));
$saveLink = Router::generate("choice-save");

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router::generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Choose courses"),
        "link" => Router::generate("choice-edit")
    ]
];

echo Blade->run("choice.edit", [
    "choosableCourses" => $choosableCourses,
    "voteCount" => $voteCount,
    "user" => $user,
    "saveLink" => $saveLink,
    "breadcrumbs" => $breadcrumbs
]);
