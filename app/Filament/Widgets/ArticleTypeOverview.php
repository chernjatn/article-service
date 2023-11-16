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

        foreach (Channel::cases() as $channel) {
            $stats[] = Stat::make($channel->getLabel(), Article::query()->where('channel_id', $channel->value)->count());
        }

        return $stats;
    }
}
