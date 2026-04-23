<?php

use Playwright\Playwright;

const SYSTEM_TEST_GROUP_A_NAME = "System Test Group A";
const SYSTEM_TEST_GROUP_B_NAME = "System Test Group B";
const SYSTEM_TEST_GROUP_A_CLEARANCE = 1;
const SYSTEM_TEST_GROUP_B_CLEARANCE = 2;
const SYSTEM_TEST_COURSE_ONE_TITLE = "System Test Course 1";
const SYSTEM_TEST_COURSE_TWO_TITLE = "System Test Course 2";
const SYSTEM_TEST_COURSE_ONE_MIN_CLEARANCE = 0;
const SYSTEM_TEST_COURSE_TWO_MIN_CLEARANCE = 1;
const SYSTEM_TEST_COURSE_ONE_MAX_PARTICIPANTS = 15;
const SYSTEM_TEST_COURSE_TWO_MAX_PARTICIPANTS = 10;
const SYSTEM_TEST_COURSE_DEFAULT_MAX_CLEARANCE = null;
const SYSTEM_TEST_COURSE_ORGANIZER = "OpenCourseMatch";
const SYSTEM_TEST_COURSE_DEFAULT_MIN_PARTICIPANTS = 0;

beforeAll(function() {
    setupExampleGroups();
    setupExampleCourses();
    setupExampleUsers();
});

test("Loading the index page works", function() {
    withBrowserPage(function($page) {
        $request = $page->goto(getSystemTestBaseUrl());

        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and($page->content())->toContain("OpenCourseMatch");
    });
});

test("Loading the login page works", function() {
    withBrowserPage(function($page) {
        $request = $page->goto(getSystemTestBaseUrl() . "/authentication/login");

        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and($page->content())->toContain("Please enter your account credentials to log in.");
    });
});

test("Loading a route that was not registered returns a 404 error", function() {
    withBrowserPage(function($page) {
        $request = $page->goto(getSystemTestBaseUrl() . "/route-that-is-not-registered");

        expect($request)->not()->toBeNull()
            ->and($request->status())->toBe(404)
            ->and($page->content())->toContain("The requested resource could not be found.");
    });
});

test("Logging in works", function() {
    $response = executeLoginRequest(getSystemTestAdminUsername(), getSystemTestAdminPassword());

    expect($response["status"])->toBe(200)
        ->and($response["body"])->toContain("Dashboard")
        ->and($response["body"])->toContain("Welcome");
});

function setupExampleUsers(): void {
    $group = \app\groups\Group::dao()->getObject(["name" => SYSTEM_TEST_GROUP_A_NAME]);
    if (!$group instanceof \app\groups\Group) {
        throw new RuntimeException("Required system test group is missing: " . SYSTEM_TEST_GROUP_A_NAME);
    }

    setupUser(
        getSystemTestAdminUsername(),
        getSystemTestAdminPassword(),
        \app\users\PermissionLevel::ADMIN,
        "System",
        "Admin",
        null
    );

    setupUser(
        "system-facilitator@example.test",
        getSystemTestDefaultPassword(),
        \app\users\PermissionLevel::FACILITATOR,
        "System",
        "Facilitator",
        null
    );

    setupUser(
        "system-user@example.test",
        getSystemTestDefaultPassword(),
        \app\users\PermissionLevel::USER,
        "System",
        "User",
        $group
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

    if ($user instanceof \app\users\User) {
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
    setupGroup(SYSTEM_TEST_GROUP_A_NAME, SYSTEM_TEST_GROUP_A_CLEARANCE);
    setupGroup(SYSTEM_TEST_GROUP_B_NAME, SYSTEM_TEST_GROUP_B_CLEARANCE);
}

function setupGroup(string $name, int $clearance): void {
    $group = \app\groups\Group::dao()->getObject(["name" => $name]);
    if (!$group instanceof \app\groups\Group) {
        $group = new \app\groups\Group();
    }

    $group->setName($name);
    $group->setClearance($clearance);

    \app\groups\Group::dao()->save($group);
}

function setupExampleCourses(): void {
    setupCourse(
        SYSTEM_TEST_COURSE_ONE_TITLE,
        SYSTEM_TEST_COURSE_ORGANIZER,
        SYSTEM_TEST_COURSE_ONE_MIN_CLEARANCE,
        SYSTEM_TEST_COURSE_DEFAULT_MAX_CLEARANCE,
        SYSTEM_TEST_COURSE_DEFAULT_MIN_PARTICIPANTS,
        SYSTEM_TEST_COURSE_ONE_MAX_PARTICIPANTS
    );
    setupCourse(
        SYSTEM_TEST_COURSE_TWO_TITLE,
        SYSTEM_TEST_COURSE_ORGANIZER,
        SYSTEM_TEST_COURSE_TWO_MIN_CLEARANCE,
        SYSTEM_TEST_COURSE_DEFAULT_MAX_CLEARANCE,
        SYSTEM_TEST_COURSE_DEFAULT_MIN_PARTICIPANTS,
        SYSTEM_TEST_COURSE_TWO_MAX_PARTICIPANTS
    );
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
    if (!$course instanceof \app\courses\Course) {
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
    $cookieFileHandle = tmpfile();
    if ($cookieFileHandle === false) {
        throw new RuntimeException("Failed to create temporary cookie file handle.");
    }

    try {
        $cookieFileMeta = stream_get_meta_data($cookieFileHandle);
        $cookieFile = $cookieFileMeta["uri"];

        $ch = curl_init(getSystemTestBaseUrl() . "/authentication/login-action");
        if ($ch === false) {
            throw new RuntimeException("Failed to initialize cURL.");
        }

        $responseBody = "";
        $statusCode = 0;

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

        try {
            $responseBody = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } finally {
            curl_close($ch);
        }

        if ($responseBody === false) {
            throw new RuntimeException("Failed to execute login request.");
        }

        return [
            "status" => $statusCode,
            "body" => strval($responseBody)
        ];
    } finally {
        fclose($cookieFileHandle);
    }
}

function getSystemTestAdminUsername(): string {
    $username = getenv("SYSTEM_TEST_ADMIN_USERNAME");
    if ($username === false || trim($username) === "") {
        static $generatedUsername = null;
        if ($generatedUsername === null) {
            $generatedUsername = "system-admin-" . bin2hex(random_bytes(8)) . "@example.test";
        }

        return $generatedUsername;
    }

    return $username;
}

function getSystemTestBaseUrl(): string {
    $baseUrl = getenv("SYSTEM_TEST_BASE_URL");
    if ($baseUrl === false || trim($baseUrl) === "") {
        return "http://localhost:3000";
    }

    return rtrim($baseUrl, "/");
}

function getSystemTestAdminPassword(): string {
    $password = getenv("SYSTEM_TEST_ADMIN_PASSWORD");
    if ($password === false || trim($password) === "") {
        return getSystemTestDefaultPassword();
    }

    return $password;
}

function getSystemTestDefaultPassword(): string {
    static $generatedPassword = null;
    if ($generatedPassword === null) {
        $generatedPassword = bin2hex(random_bytes(32));
    }

    return $generatedPassword;
}

function withBrowserPage(callable $callback): void {
    $browser = Playwright::firefox();
    try {
        $callback($browser->newPage());
    } finally {
        $browser->close();
    }
}
