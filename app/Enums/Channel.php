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
            switch ($channel->value) {
                case 'superapteka':
                    $channelIds[$channel->value] = 2;
                    break;
                case 'ozerki':
                    $channelIds[$channel->value] = 3;
                    break;
                case 'samson':
                    $channelIds[$channel->value] = 4;
                    break;
            }
        }

        return $channelIds;
    }
}
