<?php

namespace App\Http\Queries\Article;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterArticlesTradeName implements Filter
{
    const COUNT_ON_TRADE_NAME = 4;

    public function __invoke(Builder $query, $value, string $property)
    {
        $query->limit(self::COUNT_ON_TRADE_NAME);

        $cloneQuery = clone $query;

        $queryByTradeName = $cloneQuery->whereHas('tradeNames', fn ($q) => $q->where('trade_name_id', $value));

        $countArticlesNeed = self::COUNT_ON_TRADE_NAME - $queryByTradeName->get()->count();

        if (!$countArticlesNeed) {
            return $queryByTradeName;
        }

        if ($countArticlesNeed == self::COUNT_ON_TRADE_NAME) {
            return $query;
        }

        return $query->whereDoesntHave('tradeNames', fn ($q) => $q->where('trade_name_id', $value))
            ->limit($countArticlesNeed)
            ->union($queryByTradeName);
    }
}
