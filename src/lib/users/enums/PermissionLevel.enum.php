<?php

namespace app\users;

enum PermissionLevel: int implements \struktal\Auth\PermissionLevel {
    case ADMIN = 2;
    case FACILITATOR = 1;
    case USER = 0;

    public function value(): int {
        return $this->value;
    }
}
