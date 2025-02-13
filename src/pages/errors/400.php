<?php

http_response_code(400);
echo Blade->run("error", [
    "code" => 400
]);
