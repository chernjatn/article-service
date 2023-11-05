<?php

namespace App\Filament\Widgets;

use App\Enums\Channel;
use App\Models\Article;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ArticleTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = [];

        if (!empty(Channel::channelIds())) {
            foreach (Channel::channelIds() as $channel => $id) {
                $stats[] = Stat::make($channel, Article::query()->where('channel_id', $id)->count());
            }
        }

        return $stats;
    }
}
