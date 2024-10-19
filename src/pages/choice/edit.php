<?php

$user = Auth::enforceLogin(PermissionLevel::USER->value, Router::generate("index"));

if($user->getPermissionLevel() > PermissionLevel::USER->value) {
    new InfoMessage(t("Choosing courses is only available to participants and tutors."), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("index"));
}

$choosableCourses = Course::dao()->getChoosableCourses($user);
$voteCount = intval(SystemSetting::dao()->get("voteCount"));

echo Blade->run("choice.edit", [
    "choosableCourses" => $choosableCourses,
    "voteCount" => $voteCount,
    "user" => $user
]);
