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
}
