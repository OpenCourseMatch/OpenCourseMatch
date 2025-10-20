<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$validation = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "resetCourses" => CommonValidators::checkbox(),
        "resetUsers" => CommonValidators::checkbox(),
        "resetFacilitators" => CommonValidators::checkbox(),
        "resetGroups" => CommonValidators::checkbox()
    ])
    ->build();
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\struktal\validation\ValidationException $e) {
    InfoMessage->error($e->getMessage());
    Router->redirect(Router->generate("system-reset"));
}

if($post["resetUsers"] !== null) {
    $users = User::dao()->getObjects(["permissionLevel" => PermissionLevel::USER]);
    $usersCount = count($users);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the users ({$usersCount})");

    foreach($users as $account) {
        $account->preDelete();
        User::dao()->delete($account);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");
    }

    InfoMessage->info(t("\$\$count\$\$ users have been deleted.", ["count" => $usersCount]));
}

if($post["resetFacilitators"] !== null) {
    $users = User::dao()->getObjects(["permissionLevel" => PermissionLevel::FACILITATOR]);
    $usersCount = count($users);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the facilitators ({$usersCount})");

    foreach($users as $account) {
        $account->preDelete();
        User::dao()->delete($account);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the facilitator {$account->getId()} ({$account->getFullName()})");
    }

    InfoMessage->info(t("\$\$count\$\$ facilitators have been deleted.", ["count" => $usersCount]));
}

if($post["resetCourses"] !== null) {
    $courses = Course::dao()->getObjects();
    $coursesCount = count($courses);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the courses ({$coursesCount})");

    foreach($courses as $course) {
        $course->preDelete();
        Course::dao()->delete($course);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the course {$course->getId()} ({$course->getTitle()})");
    }

    InfoMessage->info(t("\$\$count\$\$ courses have been deleted.", ["count" => $coursesCount]));
}

if($post["resetGroups"] !== null) {
    $groups = Group::dao()->getObjects();
    $groupsCount = count($groups);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the courses ({$groupsCount})");

    foreach($groups as $group) {
        $group->preDelete();
        Group::dao()->delete($group);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the group {$group->getId()} ({$group->getName()})");
    }

    InfoMessage->info(t("\$\$count\$\$ groups have been deleted.", ["count" => $groupsCount]));
}

InfoMessage->success(t("The selected system data has been reset."));
Router->redirect(Router->generate("dashboard"));
