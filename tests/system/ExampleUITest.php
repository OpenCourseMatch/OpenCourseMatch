<?php

use Playwright\Playwright;

const SYSTEM_TEST_BASE_URL = "http://localhost:3000";
const SYSTEM_TEST_ADMIN_USERNAME = "system-admin@example.test";
const SYSTEM_TEST_ADMIN_PASSWORD = "TestPassword123!";

beforeAll(function() {
    setupExampleGroups();
    setupExampleCourses();
    setupExampleUsers();
});

test("Loading the index page works", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(SYSTEM_TEST_BASE_URL);

    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue()
        ->and($page->content())->toContain("OpenCourseMatch");

    $browser->close();
});

test("Loading the login page works", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(SYSTEM_TEST_BASE_URL . "/authentication/login");

    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue()
        ->and($page->content())->toContain("Please enter your account credentials to log in.");

    $browser->close();
});

test("Loading a route that was not registered returns a 404 error", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(SYSTEM_TEST_BASE_URL . "/route-that-is-not-registered");

    expect($request)->not()->toBeNull()
        ->and($request->status())->toBe(404)
        ->and($page->content())->toContain("The requested resource could not be found.");

    $browser->close();
});

test("Logging in works", function() {
    $response = executeLoginRequest(SYSTEM_TEST_ADMIN_USERNAME, SYSTEM_TEST_ADMIN_PASSWORD);

    expect($response["status"])->toBe(200)
        ->and($response["body"])->toContain("Dashboard")
        ->and($response["body"])->toContain("Welcome");
});

function setupExampleUsers(): void {
    $group = \app\groups\Group::dao()->getObject(["name" => "System Test Group A"]);

    setupUser(
        SYSTEM_TEST_ADMIN_USERNAME,
        SYSTEM_TEST_ADMIN_PASSWORD,
        \app\users\PermissionLevel::ADMIN,
        "System",
        "Admin",
        null
    );

    setupUser(
        "system-facilitator@example.test",
        "TestPassword123!",
        \app\users\PermissionLevel::FACILITATOR,
        "System",
        "Facilitator",
        null
    );

    setupUser(
        "system-user@example.test",
        "TestPassword123!",
        \app\users\PermissionLevel::USER,
        "System",
        "User",
        $group instanceof \app\groups\Group ? $group : null
    );
}

function setupUser(
    string $username,
    string $password,
    \app\users\PermissionLevel $permissionLevel,
    string $firstName,
    string $lastName,
    ?\app\groups\Group $group
): void {
    $user = \app\users\User::dao()->getObject(["username" => $username]);

    if($user instanceof \app\users\User) {
        $user->setPassword($password);
        $user->setPermissionLevel($permissionLevel);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setGroupId($group?->getId());
        $user->setLeadingCourseId(null);
        \app\users\User::dao()->save($user);

        return;
    }

    \app\users\UserService::register(
        $username,
        $password,
        $permissionLevel,
        $firstName,
        $lastName,
        $group,
        null
    );
}

function setupExampleGroups(): void {
    setupGroup("System Test Group A", 1);
    setupGroup("System Test Group B", 2);
}

function setupGroup(string $name, int $clearance): void {
    $group = \app\groups\Group::dao()->getObject(["name" => $name]);
    if(!$group instanceof \app\groups\Group) {
        $group = new \app\groups\Group();
    }

    $group->setName($name);
    $group->setClearance($clearance);

    \app\groups\Group::dao()->save($group);
}

function setupExampleCourses(): void {
    setupCourse("System Test Course 1", "OpenCourseMatch", 0, null, 0, 15);
    setupCourse("System Test Course 2", "OpenCourseMatch", 1, null, 0, 10);
}

function setupCourse(
    string $title,
    string $organizer,
    int $minClearance,
    ?int $maxClearance,
    int $minParticipants,
    int $maxParticipants
): void {
    $course = \app\courses\Course::dao()->getObject(["title" => $title]);
    if(!$course instanceof \app\courses\Course) {
        $course = new \app\courses\Course();
    }

    $course->setTitle($title);
    $course->setOrganizer($organizer);
    $course->setMinClearance($minClearance);
    $course->setMaxClearance($maxClearance);
    $course->setMinParticipants($minParticipants);
    $course->setMaxParticipants($maxParticipants);

    \app\courses\Course::dao()->save($course);
}

function executeLoginRequest(string $username, string $password): array {
    $cookieFile = tempnam(sys_get_temp_dir(), "ocm-system-test-cookies-");
    expect($cookieFile)->not()->toBeFalse();

    $ch = curl_init(SYSTEM_TEST_BASE_URL . "/authentication/login-action");
    expect($ch)->not()->toBeFalse();

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookieFile,
        CURLOPT_COOKIEFILE => $cookieFile,
        CURLOPT_POSTFIELDS => http_build_query([
            "username" => $username,
            "password" => $password
        ])
    ]);

    $responseBody = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
    unlink($cookieFile);

    expect($responseBody)->not()->toBeFalse();

    return [
        "status" => $statusCode,
        "body" => strval($responseBody)
    ];
}
