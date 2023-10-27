<?php

namespace App\Filament\Widgets;

use App\Enums\Channel;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];

        if (!empty(Channel::channelIds())) {
            foreach (Channel::channelIds() as $channel => $id) {
                $stats[] = Stat::make($channel, Post::query()->where('channel', $id)->count());
            }
        }

        return $stats;
    }
}
