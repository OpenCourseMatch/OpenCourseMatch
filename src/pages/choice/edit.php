<?php

$user = Auth->enforceLogin(PermissionLevel::USER->value, Router->generate("index"));

if($user->getPermissionLevel() > PermissionLevel::USER->value) {
    InfoMessage->error(t("Choosing courses is only available to participants and tutors."));
    Router->redirect(Router->generate("index"));
}

if(SystemStatus::dao()->get("userActionsAllowed") !== "true") {
    InfoMessage->error(t("The course selection has already been disabled. You can no longer update your course preferences."));
    Router->redirect(Router->generate("index"));
}

$choosableCourses = Course::dao()->getChoosableCourses($user);
$choiceCount = intval(SystemSetting::dao()->get("choiceCount"));
$saveLink = Router->generate("choice-save");

$breadcrumbs = [
    [
        "name" => t("Dashboard"),
        "link" => Router->generate("dashboard"),
        "iconComponent" => "components.icons.dashboard"
    ],
    [
        "name" => t("Choose courses"),
        "link" => Router->generate("choice-edit")
    ]
];

echo Blade->run("choice.edit", [
    "choosableCourses" => $choosableCourses,
    "choiceCount" => $choiceCount,
    "user" => $user,
    "saveLink" => $saveLink,
    "breadcrumbs" => $breadcrumbs
]);
