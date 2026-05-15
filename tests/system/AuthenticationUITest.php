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

test("Change password", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    // Log in first
    handleLoginUI($page, SYSTEM_TEST_ADMIN_USERNAME, SYSTEM_TEST_ADMIN_PASSWORD);

    // Navigate to change password page
    $page->goto(Router->generate("account-settings-change-password", [], true));

    // Fill out the change password form
    $page->locator("input[name='current-password']")->fill(SYSTEM_TEST_ADMIN_PASSWORD);
    $page->locator("input[name='new-password']")->fill("NewAdminPassword123!");
    $page->locator("input[name='new-password-repeat']")->fill("NewAdminPassword123!");
    $page->locator("button[type='submit']")->click();

    // Expect a success message and a redirect
    expect($page->url())->toBe(Router->generate("account-settings", [], true))
        ->and(pageHasInfoMessageOfType($page, \struktal\InfoMessage\InfoMessageType::SUCCESS))->toBeTrue();

    // Navigate to change password page again
    $page->goto(Router->generate("account-settings-change-password", [], true));

    // Change the password back to the original one
    $page->locator("input[name='current-password']")->fill("NewAdminPassword123!");
    $page->locator("input[name='new-password']")->fill(SYSTEM_TEST_ADMIN_PASSWORD);
    $page->locator("input[name='new-password-repeat']")->fill(SYSTEM_TEST_ADMIN_PASSWORD);
    $page->locator("button[type='submit']")->click();

    // Expect a success message and a redirect
    expect($page->url())->toBe(Router->generate("account-settings", [], true))
        ->and(pageHasInfoMessageOfType($page, \struktal\InfoMessage\InfoMessageType::SUCCESS))->toBeTrue();
});
