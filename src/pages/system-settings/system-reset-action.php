<?php

$user = Auth->requireLogin(\app\users\PermissionLevel::ADMIN, Router->generate("index"));

$post = Validation->create()
    ->withErrorMessage(t("Please fill out all the required fields."))
    ->array()
    ->required()
    ->children([
        "resetCourses" => CommonValidators::checkbox(),
        "resetUsers" => CommonValidators::checkbox(),
        "resetFacilitators" => CommonValidators::checkbox(),
        "resetGroups" => CommonValidators::checkbox()
    ])
    ->validate($_POST, function(\struktal\validation\ValidationException $e) {
        InfoMessage->error($e->getMessage());
        Router->redirect(Router->generate("system-reset"));
    });

if($post["resetUsers"] !== null) {
    $users = \app\users\User::dao()->getObjects(["permissionLevel" => \app\users\PermissionLevel::USER]);
    $usersCount = count($users);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the users ({$usersCount})");

    foreach($users as $account) {
        \app\users\UserService::delete($account);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");
    }

    InfoMessage->info(t("\$\$count\$\$ users have been deleted.", ["count" => $usersCount]));
}

if($post["resetFacilitators"] !== null) {
    $users = \app\users\User::dao()->getObjects(["permissionLevel" => \app\users\PermissionLevel::FACILITATOR]);
    $usersCount = count($users);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the facilitators ({$usersCount})");

    foreach($users as $account) {
        \app\users\UserService::delete($account);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the facilitator {$account->getId()} ({$account->getFullName()})");
    }

    InfoMessage->info(t("\$\$count\$\$ facilitators have been deleted.", ["count" => $usersCount]));
}

if($post["resetCourses"] !== null) {
    $courses = \app\courses\Course::dao()->getObjects();
    $coursesCount = count($courses);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the courses ({$coursesCount})");

    foreach($courses as $course) {
        \app\courses\CourseService::delete($course);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the course {$course->getId()} ({$course->getTitle()})");
    }

    InfoMessage->info(t("\$\$count\$\$ courses have been deleted.", ["count" => $coursesCount]));
}

if($post["resetGroups"] !== null) {
    $groups = \app\groups\Group::dao()->getObjects();
    $groupsCount = count($groups);

    Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the courses ({$groupsCount})");

    foreach($groups as $group) {
        \app\groups\GroupService::delete($group);

        Logger->tag("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the group {$group->getId()} ({$group->getName()})");
    }

    InfoMessage->info(t("\$\$count\$\$ groups have been deleted.", ["count" => $groupsCount]));
}

InfoMessage->success(t("The selected system data has been reset."));
Router->redirect(Router->generate("dashboard"));
