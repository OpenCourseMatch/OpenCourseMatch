<?php

use Playwright\Playwright;

const LOGIN_ROUTE = "/authentication/login";
const NOT_FOUND_MESSAGE = "The requested resource could not be found.";
const LOGIN_LINK_SELECTOR = "//a[@href='/authentication/login']";
const LOGIN_FORM_SELECTOR = "//form[@method='post' and @action='/authentication/login']";

function testBaseUrl(): string {
    return getenv("OCM_TEST_BASE_URL") ?: "http://localhost:3000";
}

function createXPath(string $html): \DOMXPath {
    $document = new \DOMDocument();
    libxml_use_internal_errors(true);
    $document->loadHTML($html);
    libxml_clear_errors();

    return new \DOMXPath($document);
}

function visitPage(string $path, callable $assertions): void {
    $browser = Playwright::firefox();
    try {
        $page = $browser->newPage();
        $request = $page->goto(testBaseUrl() . $path);
        $assertions($page, $request, createXPath($page->content()));
    } finally {
        $browser->close();
    }
}

function elementExists(\DOMXPath $xPath, string $selector): bool {
    try {
        return (bool) $xPath->evaluate("boolean($selector)");
    } catch (\Throwable) {
        return false;
    }
}

test("Landing page loads and links to login", function() {
    visitPage("/", function($page, $request, \DOMXPath $xPath) {
        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and(elementExists($xPath, LOGIN_LINK_SELECTOR))->toBeTrue();
    });
});

test("Login page displays credential form fields", function() {
    visitPage(LOGIN_ROUTE, function($page, $request, \DOMXPath $xPath) {
        expect($request)->not()->toBeNull()
            ->and($request->ok())->toBeTrue()
            ->and(elementExists($xPath, "//input[@name='username']"))->toBeTrue()
            ->and(elementExists($xPath, "//input[@name='password']"))->toBeTrue()
            ->and(elementExists($xPath, LOGIN_FORM_SELECTOR))->toBeTrue();
    });
});

test("Unknown route renders the 404 error page", function() {
    visitPage("/this-route-does-not-exist", function($page, $request, \DOMXPath $xPath) {
        $content = $page->content();

        expect($request)->not()->toBeNull()
            ->and($request->status())->toBe(404)
            ->and(str_contains($content, NOT_FOUND_MESSAGE))->toBeTrue();
    });
});
