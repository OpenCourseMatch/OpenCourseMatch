<?php

$user = Auth::enforceLogin(PermissionLevel::ADMIN->value, Router::generate("index"));

$validation = \validation\Validator::create([
    \validation\IsRequired::create(),
    \validation\IsArray::create(),
    \validation\HasChildren::create([
        "resetCourses" => \validation\Validator::create([
            \validation\IsInteger::create()
        ]),
        "resetUsers" => \validation\Validator::create([
            \validation\IsInteger::create()
        ]),
        "resetFacilitators" => \validation\Validator::create([
            \validation\IsInteger::create()
        ]),
        "resetGroups" => \validation\Validator::create([
            \validation\IsInteger::create()
        ])
    ])
])->setErrorMessage(t("Please fill out all the required fields."));
try {
    $post = $validation->getValidatedValue($_POST);
} catch(\validation\ValidationException $e) {
    new InfoMessage($e->getMessage(), InfoMessageType::ERROR);
    Comm::redirect(Router::generate("system-reset"));
}

if($post["resetUsers"] !== null) {
    $users = User::dao()->getObjects(["permissionLevel" => PermissionLevel::USER->value]);
    $usersCount = count($users);

    Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the users ({$usersCount})");

    foreach($users as $account) {
        $account->preDelete();
        User::dao()->delete($account);

        Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the user {$account->getId()} ({$account->getFullName()})");
    }

    new InfoMessage(t("\$\$count\$\$ users have been deleted.", ["count" => $usersCount]), InfoMessageType::INFO);
}

if($post["resetFacilitators"] !== null) {
    $users = User::dao()->getObjects(["permissionLevel" => PermissionLevel::FACILITATOR->value]);
    $usersCount = count($users);

    Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the facilitators ({$usersCount})");

    foreach($users as $account) {
        $account->preDelete();
        User::dao()->delete($account);

        Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the facilitator {$account->getId()} ({$account->getFullName()})");
    }

    new InfoMessage(t("\$\$count\$\$ facilitators have been deleted.", ["count" => $usersCount]), InfoMessageType::INFO);
}

if($post["resetCourses"] !== null) {
    $courses = Course::dao()->getObjects();
    $coursesCount = count($courses);

    Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the courses ({$coursesCount})");

    foreach($courses as $course) {
        $course->preDelete();
        Course::dao()->delete($course);

        Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the course {$course->getId()} ({$course->getTitle()})");
    }

    new InfoMessage(t("\$\$count\$\$ courses have been deleted.", ["count" => $coursesCount]), InfoMessageType::INFO);
}

if($post["resetGroups"] !== null) {
    $groups = Group::dao()->getObjects();
    $groupsCount = count($groups);

    Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) is resetting the courses ({$groupsCount})");

    foreach($groups as $group) {
        $group->preDelete();
        Group::dao()->delete($group);

        Logger::getLogger("SystemReset")->info("User {$user->getId()} ({$user->getFullName()}, PL {$user->getPermissionLevel()}) deleted the group {$group->getId()} ({$group->getName()})");
    }

    new InfoMessage(t("\$\$count\$\$ groups have been deleted.", ["count" => $groupsCount]), InfoMessageType::INFO);
}

new InfoMessage(t("The selected system data has been reset."), InfoMessageType::SUCCESS);
Comm::redirect(Router::generate("dashboard"));
