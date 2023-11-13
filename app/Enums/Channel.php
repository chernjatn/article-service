<?php

namespace App\Enums;

enum Channel: string
{
    case SUPERAPTEKA = 'superapteka';
    case OZERKI = 'ozerki';
    case SAMSON = 'samson';

    public static function channelIds(): array
    {
        $channelIds = [];

        foreach (self::cases() as $channel) {
            $channelIds[$channel->value] = $channel->projectId();
        }

        return $channelIds;
    }

    public static function fromHost(string $host): Channel
    {
        return match ($host) {
            'https://superapteka.ru' => self::SUPERAPTEKA,
            'https://ozerki.ru' => self::OZERKI,
            'https://samson-pharma.ru' => self::SAMSON,
            default => throw new \Exception('Unsupported host'),
        };
    }

    public function projectId(): string {
        return static::getId($this);
    }

    public static function getId(self $value): string {
        return match ($value) {
            self::SUPERAPTEKA => 2,
            self::OZERKI => 3,
            self::SAMSON => 4,
        };
    }
}
