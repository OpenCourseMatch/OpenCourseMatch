<?php

namespace validation;

class ValidationException extends \Exception {
    private array $errorFields;

    public function __construct(array $errorFields, string $message = "Invalid input") {
        parent::__construct($message);
        $this->errorFields = $errorFields;
    }

    public function getErrorFields(): array {
        return $this->errorFields;
    }
}
