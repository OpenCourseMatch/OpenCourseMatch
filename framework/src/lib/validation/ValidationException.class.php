<?php

namespace validation;

class ValidationException extends \Exception {

    public function __construct(string $message = "Invalid input") {
        parent::__construct($message);
    }
}
