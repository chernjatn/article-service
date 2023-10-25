<?php

namespace App\Enums;

enum Channel: string
{
    case SUPERAPTEKA = 'superapteka';
    case OZERKI = 'ozerki';
    case SAMSON = 'samson';

    public function title(): string
    {
        return match ($this) {
            self::SUPERAPTEKA => 'Супераптека',
            self::OZERKI => 'Озерки',
            self::SAMSON => 'Самсон',
        };
    }

    public function valueColumn(): string
    {
        return match ($this) {
            self::SUPERAPTEKA => 2,
            self::OZERKI => 3,
            self::SAMSON => 4
        };
    }
}
