<?php

namespace validation;

interface ValidatorInterface {
    public static function create(bool $required);

    /**
     * @throws ValidationException
     */
    public function getValidatedValue(mixed &$input): mixed;
}
