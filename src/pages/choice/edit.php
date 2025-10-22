<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::USER, Router->generate("index"));

if($user->getPermissionLevel()->value > \app\users\PermissionLevel::USER->value) {
    InfoMessage->error(t("Choosing courses is only available to participants and tutors."));
    Router->redirect(Router->generate("index"));
}

if(\app\settings\SystemStatus::dao()->get("userActionsAllowed") !== "true") {
    InfoMessage->error(t("The course selection has already been disabled. You can no longer update your course preferences."));
    Router->redirect(Router->generate("index"));
}

$choosableCourses = \app\courses\Course::dao()->getChoosableCourses($user);
$choiceCount = intval(\app\settings\SystemSetting::dao()->get("choiceCount"));
$saveLink = Router->generate("choice-save");

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "icons.dashboard"
    ],
    [
        "name" => t("Choose courses"),
        "link" => Router->generate("choice-edit")
    ]
];

echo Blade->run("pages.choice.edit", [
    "choosableCourses" => $choosableCourses,
    "choiceCount" => $choiceCount,
    "user" => $user,
    "saveLink" => $saveLink,
    "breadcrumbs" => $breadcrumbs
]);
