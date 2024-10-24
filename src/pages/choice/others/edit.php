<?php

$user = Auth::enforceLogin(PermissionLevel::FACILITATOR->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "user" => \validation\Validator::create([
            \validation\IsInDatabase::create(User::dao(), [
                "permissionLevel" => PermissionLevel::USER->value,
            ])->setErrorMessage(t("The user of which the choice should be edited does not exist."))
        ])
    ])
]);
try {
    $get = $validation->getValidatedValue($_GET);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("users-overview"));
}

$account = $get["user"];

$choosableCourses = Course::dao()->getChoosableCourses($account);
$voteCount = intval(SystemSetting::dao()->get("voteCount"));
$saveLink = Router::generate("choice-save-others", ["user" => $account->getId()]);

echo Blade->run("choice.edit", [
    "choosableCourses" => $choosableCourses,
    "voteCount" => $voteCount,
    "user" => $account,
    "saveLink" => $saveLink
]);
