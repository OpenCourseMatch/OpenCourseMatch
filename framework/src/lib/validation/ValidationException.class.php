<?php

namespace validation;

class ValidationException extends \Exception {

    public function __construct(?string $message = null) {
        parent::__construct($message);
    }
}
