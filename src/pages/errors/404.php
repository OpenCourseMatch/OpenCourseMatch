<?php

http_response_code(404);
echo Blade->run("pages.error", [
    "code" => 404
]);
