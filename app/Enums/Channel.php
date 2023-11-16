<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Channel: int implements HasLabel
{
//    case SUPERAPTEKA = 'superapteka';
//    case OZERKI = 'ozerki';
//    case SAMSON = 'samson';
//    case STOLETOV = 'stoletov';

    case SUPERAPTEKA = 2;
    case OZERKI = 3;
    case SAMSON = 4;
    case STOLETOV = 5;

//    public static function channelIds(): array
//    {
//        $channelIds = [];
//
//        foreach (self::cases() as $channel) {
//            $channelIds[$channel->value] = $channel->projectId();
//        }
//
//        return $channelIds;
//    }
//
//    public static function fromHost(string $host): Channel
//    {
//        return match ($host) {
//            'https://superapteka.ru' => self::SUPERAPTEKA,
//            'https://ozerki.ru' => self::OZERKI,
//            'https://samson-pharma.ru' => self::SAMSON,
//            'https://stoletov.ru' => self::STOLETOV,
//            default => throw new \Exception('Unsupported host'),
//        };
//    }
//
//    public function projectId(): string
//    {
//        return static::getId($this);
//    }
//
    public static function getId(string $value): int
    {
        return match ($value) {
            'samson-farma' => 4,
            'ozerki' => 3,
            'superapteka' => 2,
            'stoletov' => 5,
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
}
