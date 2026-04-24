<?php

use Playwright\Playwright;

const SYSTEM_TEST_ADMIN_USERNAME = "system-admin";
const SYSTEM_TEST_ADMIN_PASSWORD = "AdminPass123!";
const SYSTEM_TEST_FACILITATOR_USERNAME = "system-facilitator";
const SYSTEM_TEST_FACILITATOR_PASSWORD = "FacilitatorPass123!";
const SYSTEM_TEST_USER_USERNAME = "system-user";
const SYSTEM_TEST_USER_PASSWORD = "UserPass123!";
const SYSTEM_TEST_PASSWORD_USER_USERNAME = "system-password-user";
const SYSTEM_TEST_PASSWORD_USER_PASSWORD = "PasswordUserPass123!";
const SYSTEM_TEST_ASSIGNMENT_USER_USERNAME = "system-assignment-user";
const SYSTEM_TEST_ASSIGNMENT_USER_PASSWORD = "AssignmentUserPass123!";

const SYSTEM_TEST_GROUP_MAIN = "System Test Group Main";
const SYSTEM_TEST_GROUP_ALT = "System Test Group Alt";

const SYSTEM_TEST_COURSE_ONE = "System Test Course One";
const SYSTEM_TEST_COURSE_TWO = "System Test Course Two";
const SYSTEM_TEST_COURSE_THREE = "System Test Course Three";

function ensureSystemTestData(): void {
    static $initialized = false;
    if($initialized) {
        return;
    }

    \app\settings\SystemSetting::dao()->setDefaults();
    \app\settings\SystemSetting::dao()->set("choiceCount", "3");
    \app\settings\SystemStatus::dao()->set("userActionsAllowed", "true");
    \app\settings\SystemStatus::dao()->set("algorithmRunning", "false");
    \app\settings\SystemStatus::dao()->set("coursesAssigned", "true");
    \app\settings\SystemStatus::dao()->set("courseAssignmentPublic", "false");

    $mainGroup = upsertSystemTestGroup(SYSTEM_TEST_GROUP_MAIN, 2);
    $altGroup = upsertSystemTestGroup(SYSTEM_TEST_GROUP_ALT, 1);

    $courseOne = upsertSystemTestCourse(SYSTEM_TEST_COURSE_ONE, 0, 20);
    $courseTwo = upsertSystemTestCourse(SYSTEM_TEST_COURSE_TWO, 0, 20);
    $courseThree = upsertSystemTestCourse(SYSTEM_TEST_COURSE_THREE, 0, 20);

    upsertSystemTestUser(
        SYSTEM_TEST_ADMIN_USERNAME,
        SYSTEM_TEST_ADMIN_PASSWORD,
        \app\users\PermissionLevel::ADMIN,
        "System",
        "Admin",
        null,
        null
    );

    upsertSystemTestUser(
        SYSTEM_TEST_FACILITATOR_USERNAME,
        SYSTEM_TEST_FACILITATOR_PASSWORD,
        \app\users\PermissionLevel::FACILITATOR,
        "System",
        "Facilitator",
        null,
        null
    );

    upsertSystemTestUser(
        SYSTEM_TEST_USER_USERNAME,
        SYSTEM_TEST_USER_PASSWORD,
        \app\users\PermissionLevel::USER,
        "System",
        "User",
        $mainGroup->getId(),
        null
    );

    upsertSystemTestUser(
        SYSTEM_TEST_PASSWORD_USER_USERNAME,
        SYSTEM_TEST_PASSWORD_USER_PASSWORD,
        \app\users\PermissionLevel::USER,
        "System",
        "Password",
        $mainGroup->getId(),
        null
    );

    upsertSystemTestUser(
        SYSTEM_TEST_ASSIGNMENT_USER_USERNAME,
        SYSTEM_TEST_ASSIGNMENT_USER_PASSWORD,
        \app\users\PermissionLevel::USER,
        "System",
        "Assignment",
        $altGroup->getId(),
        null
    );

    \app\assignments\AssignmentService::setAssignedCourseForUser(getSystemTestUser(SYSTEM_TEST_ASSIGNMENT_USER_USERNAME), null);
    \app\choices\ChoiceService::deleteChoicesForUser(getSystemTestUser(SYSTEM_TEST_USER_USERNAME));
    \app\choices\ChoiceService::setChoicesForUser(getSystemTestUser(SYSTEM_TEST_USER_USERNAME), [
        $courseOne,
        $courseTwo,
        $courseThree
    ]);

    $initialized = true;
}

function upsertSystemTestGroup(string $name, int $clearance): \app\groups\Group {
    $group = \app\groups\Group::dao()->getObject(["name" => $name]) ?? new \app\groups\Group();
    $group->setName($name);
    $group->setClearance($clearance);
    \app\groups\Group::dao()->save($group);

    return $group;
}

function upsertSystemTestCourse(string $title, int $minClearance, int $maxParticipants): \app\courses\Course {
    $course = \app\courses\Course::dao()->getObject(["title" => $title]) ?? new \app\courses\Course();
    $course->setTitle($title);
    $course->setOrganizer("System Tests");
    $course->setMinClearance($minClearance);
    $course->setMaxClearance(null);
    $course->setMinParticipants(0);
    $course->setMaxParticipants($maxParticipants);
    \app\courses\Course::dao()->save($course);

    return $course;
}

function upsertSystemTestUser(
    string $username,
    string $password,
    \app\users\PermissionLevel $permissionLevel,
    string $firstName,
    string $lastName,
    ?int $groupId,
    ?int $leadingCourseId
): \app\users\User {
    $user = \app\users\User::dao()->getObject(["username" => $username]) ?? new \app\users\User();
    $user->setUsername($username);
    $user->setPassword($password);
    $user->setEmail($username);
    $user->setEmailVerified(true);
    $user->setPermissionLevel($permissionLevel);
    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setGroupId($groupId);
    $user->setLeadingCourseId($leadingCourseId);
    $user->setShowHelpBoxes(true);
    $user->setLastLogin(null);
    $user->setOneTimePassword(null);
    $user->setOneTimePasswordExpiration(null);
    \app\users\User::dao()->save($user);

    return $user;
}

function getSystemTestUser(string $username): \app\users\User {
    $user = \app\users\User::dao()->getObject(["username" => $username]);
    expect($user)->toBeInstanceOf(\app\users\User::class);
    return $user;
}

function withBrowserPage(callable $callback): void {
    $browser = Playwright::firefox();
    try {
        $page = $browser->newPage();
        $callback($page);
    } finally {
        $browser->close();
    }
}

function loginWithCredentials(object $page, string $username, string $password): void {
    $page->goto(Router->generate("auth-login", [], true));
    $page->locator("#username")->fill($username);
    $page->locator("#password")->fill($password);
    $page->locator("button[type=\"submit\"]")->click();
    waitForTextOnPage($page, "Dashboard");
}

function logoutCurrentUser(object $page): void {
    $page->goto(Router->generate("auth-logout", [], true));
}

function waitForTextOnPage(object $page, string $text, int $attempts = 20, int $microsecondsBetweenAttempts = 250000): void {
    waitForCondition(function() use ($page, $text) {
        return str_contains($page->content(), $text);
    }, $attempts, $microsecondsBetweenAttempts);
}

function waitForCondition(callable $condition, int $attempts = 20, int $microsecondsBetweenAttempts = 250000): void {
    for($i = 0; $i < $attempts; $i++) {
        if($condition()) {
            return;
        }
        usleep($microsecondsBetweenAttempts);
    }

    expect($condition())->toBeTrue();
}
