<?php

use Playwright\Playwright;

const TEST_BASE_URL = "http://localhost:3000";

test("Landing page loads and links to login", function() {
    $browser = Playwright::firefox();

    try {
        $page = $browser->newPage();
        $request = $page->goto(TEST_BASE_URL . "/");

        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and(str_contains($page->content(), "href=\"/authentication/login\""))->toBeTrue();
    } finally {
        $browser->close();
    }
});

test("Login page displays credential form fields", function() {
    $browser = Playwright::firefox();

    try {
        $page = $browser->newPage();
        $request = $page->goto(TEST_BASE_URL . "/authentication/login");
        $content = $page->content();

        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and(str_contains($content, "name=\"username\""))->toBeTrue()
            ->and(str_contains($content, "name=\"password\""))->toBeTrue()
            ->and(str_contains($content, "<form method=\"post\" action=\"/authentication/login\">"))->toBeTrue();
    } finally {
        $browser->close();
    }
});

test("Unknown route renders the 404 error page", function() {
    $browser = Playwright::firefox();

    try {
        $page = $browser->newPage();
        $request = $page->goto(TEST_BASE_URL . "/this-route-does-not-exist");
        $content = $page->content();

        expect($request)->not()->toBeNull()
            ->and($request->status())->toBe(404)
            ->and(str_contains($content, "The requested resource could not be found."))->toBeTrue();
    } finally {
        $browser->close();
    }
});
