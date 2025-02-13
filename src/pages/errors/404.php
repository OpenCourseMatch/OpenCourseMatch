<?php

http_response_code(404);
echo Blade->run("error", [
    "code" => 404
]);
