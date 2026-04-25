<?php

use Playwright\Playwright;

require_once(__DIR__ . "/SystemTestSetup.php");

beforeAll(function() {
    setupSystemTestData();
});

test("Login page is reachable", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(Router->generate("auth-login", [], true));
    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue();
});

test("Login as administrator", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    handleLoginUI($page, SYSTEM_TEST_ADMIN_USERNAME, SYSTEM_TEST_ADMIN_PASSWORD);

    expect($page->url())->toBe(Router->generate("dashboard", [], true))
        ->and($page->locator("h1")->textContent())->not()->toBeEmpty();
});

test("Login with invalid credentials", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    // Wrong username and wrong password
    handleLoginUI($page, "invalid-username", "invalid-password");
    expect($page->url())->toBe(Router->generate("auth-login", [], true))
        ->and(pageHasInfoMessageOfType($page, \struktal\InfoMessage\InfoMessageType::ERROR))->toBeTrue();

    // Correct username, wrong password
    handleLoginUI($page, SYSTEM_TEST_ADMIN_USERNAME, "invalid-password");
    expect($page->url())->toBe(Router->generate("auth-login", [], true))
        ->and(pageHasInfoMessageOfType($page, \struktal\InfoMessage\InfoMessageType::ERROR))->toBeTrue();

    // Wrong username, correct password
    handleLoginUI($page, "invalid-username", SYSTEM_TEST_ADMIN_PASSWORD);
    expect($page->url())->toBe(Router->generate("auth-login", [], true))
        ->and(pageHasInfoMessageOfType($page, \struktal\InfoMessage\InfoMessageType::ERROR))->toBeTrue();
});
