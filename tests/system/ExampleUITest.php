<?php

use Playwright\Playwright;

test("Example UI test", function() {
    $browser = Playwright::firefox();
    $page = $browser->newPage();

    $request = $page->goto(Router->generate("index", [], true));
    expect($request)->not()->toBeNull()
        ->and($request->ok())->toBeTrue();

    // Interaction with the page...
});
