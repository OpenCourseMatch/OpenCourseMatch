<?php

namespace validation;

class GenericValidator {
    private ?string $errorMessage = null;

    public function getErrorMessage(): ?string {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $message): GenericValidator {
        $this->errorMessage = $message;
        return $this;
    }

    public function failValidation(): void {
        throw new ValidationException($this->getErrorMessage());
    }
}
