<?php

namespace App\Enums;

enum UserRole: string
{
    case Administrator = 'administrator';
    case Staff = 'staff';

    public function getLabel(): string
    {
        return match ($this) {
            self::Administrator => 'Administrator',
            self::Staff => 'Staff',
        };
    }
}
