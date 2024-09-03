<?php

namespace validation;

interface ValidatorInterface {
    public function __construct(bool $required);

    public function validate(mixed &$input): bool;
    public function getValidatedValue(mixed &$input): mixed;
}
