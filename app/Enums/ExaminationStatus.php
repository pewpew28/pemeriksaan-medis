<?php

namespace App\Enums;

enum ExaminationStatus: string
{
    case PENDING = 'pending';
    case CREATED = 'created';
    case PENDING_CASH_PAYMENT = 'pending_cash_payment';
    case PENDING_PAYMENT = 'pending_payment';
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case FAILED = 'failed';
    case PAID = 'paid';

    public static function getPayableStatuses(): array
    {
        return [
            self::PENDING->value,
            self::CREATED->value,
            self::PENDING_CASH_PAYMENT->value,
            self::PENDING_PAYMENT->value
        ];
    }

    public static function getCashPayableStatuses(): array
    {
        return [
            self::PENDING_CASH_PAYMENT->value,
            self::CREATED->value
        ];
    }

    public static function getFinalStatuses(): array
    {
        return [
            self::COMPLETED->value,
            self::PAID->value,
            self::CANCELLED->value,
            self::FAILED->value
        ];
    }
}
