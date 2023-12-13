<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Channel: string implements HasLabel
{
    case SUPERAPTEKA = 'superapteka';
    case OZERKI = 'ozerki';
    case SAMSON = 'samson';
    case STOLETOV = 'stoletov';

    public function title(): string
    {
        return match ($this) {
            self::SUPERAPTEKA => 'Супераптека',
            self::OZERKI => 'Озерки',
            self::SAMSON => 'Самсон',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SUPERAPTEKA => 'superapteka',
            self::OZERKI => 'ozerki',
            self::SAMSON => 'samson',
            self::STOLETOV => 'stoletov',
        };
    }

    public function host(): string
    {
        return match ($this) {
            self::SUPERAPTEKA => 'https://superapteka.ru',
            self::OZERKI => 'https://ozerki.ru',
            self::SAMSON => 'https://rc-dev.samson-pharma.ru', //TODO
            self::STOLETOV => 'https://stoletov.ru', //TODO
        };
    }
}
