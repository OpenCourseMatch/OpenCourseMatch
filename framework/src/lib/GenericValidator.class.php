<?php

namespace validation;

class GenericValidator {
    private string $errorMessage = "";

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $message): GenericValidator {
        $this->errorMessage = $message;
        return $this;
    }

    public function failValidation(bool $isCritical = false): void {
        throw new ValidationException($this->getErrorMessage(), $isCritical);
    }
}
