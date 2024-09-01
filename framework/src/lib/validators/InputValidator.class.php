<?php

namespace validation;

interface InputValidator {
    public function __construct(bool $required);

    public function validate(mixed &$input): bool;
    public function getValidatedValue(mixed &$input): mixed;
}
