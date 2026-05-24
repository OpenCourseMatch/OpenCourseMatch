<?php

use Playwright\Playwright;

require_once(__DIR__ . "/SystemTestSetup.php");

beforeAll(function() {
    setupSystemTestData();
});

test("User import page is reachable for facilitators", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    handleLoginUI($page, SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD);

    $request = $page->goto(Router->generate("users-import", [], true));
    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue()
        ->and(trim($page->locator("h1")->textContent()))->toBe("Import users");
});

test("Import users from CSV file", function() {
    $suffix = uniqid("csv", false);
    $context = Playwright::firefox();
    $page = $context->newPage();

    handleLoginUI($page, SYSTEM_TEST_FACILITATOR_USERNAME, SYSTEM_TEST_FACILITATOR_PASSWORD);

    $page->goto(Router->generate("users-import", [], true));

    $lastNameOne = "CsvLast{$suffix}";
    $firstNameOne = "CsvFirst{$suffix}";
    $lastNameTwo = "CsvLastTwo{$suffix}";
    $firstNameTwo = "CsvFirstTwo{$suffix}";

    $csvPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "ocm-users-import-{$suffix}.csv";
    $csvContent = "{$lastNameOne},{$firstNameOne}\n{$lastNameTwo},{$firstNameTwo}\n";
    file_put_contents($csvPath, $csvContent);

    try {
        $page->setInputFiles("input#file", [$csvPath]);
        $response = $page->waitForResponse(
            Router->generate("users-import-action", [], true),
            [
                "action" => "document.querySelector(\"button[type='submit']\").click()"
            ]
        );
        $headers = array_change_key_case($response->headers(), CASE_LOWER);
        $pdfContent = $response->body();
        expect($response->ok())->toBeTrue()
            ->and($headers["content-type"] ?? null)->toBe("application/pdf")
            ->and(substr($pdfContent, 0, 4))->toBe("%PDF");
    } finally {
        if (is_file($csvPath)) {
            @unlink($csvPath);
        }
    }
});
