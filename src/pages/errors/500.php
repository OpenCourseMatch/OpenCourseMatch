<?php

http_response_code(500);
echo Blade->run("pages.error", [
    "code" => 500
]);
