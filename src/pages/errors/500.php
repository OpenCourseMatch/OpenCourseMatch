<?php

http_response_code(500);
echo Blade->run("error", [
    "code" => 500
]);
