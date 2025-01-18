<?php

$post = [];
try {
    $post = json_decode(file_get_contents("php://input"), true);
} catch(Exception $e) {
    $post = [];
}

if(!empty($post["message"])) {
    $message = $post["message"];
    $variables = $post["variables"] ?? [];

    echo htmlspecialchars(t($message, $variables));
}
