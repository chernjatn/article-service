<?php

namespace App\Http\Queries\Article;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterArticlesTradeName implements Filter
{
    const COUNT_ON_TRADE_NAME = 4;

    public function __invoke(Builder $query, $value, string $property)
    {
        $query->take(self::COUNT_ON_TRADE_NAME);

        $queryByTradeName = $this->setClauseByTradeName($query->clone(), $value);

        $countArticlesNeed = self::COUNT_ON_TRADE_NAME - $queryByTradeName->get()->count();

        switch ($countArticlesNeed) {
            case 0:
                return $this->setClauseByTradeName($query, $value);
            case self::COUNT_ON_TRADE_NAME:
                return $query;
            default:
                return $query->whereDoesntHave('tradeNames', fn ($q) => $q->where('trade_name_id', $value))
                    ->limit($countArticlesNeed)
                    ->union($queryByTradeName);
        }
    }

    public function setClauseByTradeName(Builder $query, $value): Builder
    {
        return $query->whereHas('tradeNames', fn ($q) => $q->where('trade_name_id', (int) $value));
    }
}
