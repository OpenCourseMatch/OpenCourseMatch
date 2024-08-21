<?php

if(!empty($_POST["message"])) {
    $message = $_POST["message"];
    $variables = $_POST["variables"] ?? [];

    echo htmlspecialchars(t($message, $variables));
}
