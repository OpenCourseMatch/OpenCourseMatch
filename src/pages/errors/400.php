<?php

http_response_code(400);
echo Blade->run("pages.error", [
    "code" => 400
]);
