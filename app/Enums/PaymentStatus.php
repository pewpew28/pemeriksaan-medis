<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case PAID = 'PAID';
    case CANCELLED = 'CANCELLED';
    case FAILED = 'FAILED';
    case EXPIRED = 'EXPIRED';

    public static function getActiveStatuses(): array
    {
        return [self::PENDING->value, self::ACTIVE->value];
    }

    public static function getCompletedStatuses(): array
    {
        return [self::PAID->value];
    }

    public static function getFailedStatuses(): array
    {
        return [self::CANCELLED->value, self::FAILED->value, self::EXPIRED->value];
    }
}
