<?php

namespace App\Models\Repository;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleRepository
{
    public function getArticlesByCountNeed(int $limit, Collection $articles, array $addClause = []): Collection
    {
        $baseQuery = static function (int $limit) use ($articles, $addClause) {
            return Article::query()
                ->with(['heading', 'media'])
                ->compact()
                ->whereNotIn('id', $articles->pluck('id'))
                ->when(!empty($addClause), function ($q) use ($addClause) {
                    foreach ($addClause as $clause) {
                        return $q->where($clause['column'], $clause['operator'], $clause['value']);
                    }
                })
                ->orderByDesc('id')
                ->limit($limit);
        };

        $countArticlesNeed = $limit - $articles->count();

        switch ($countArticlesNeed) {
            case 0:
                return $articles;
            case $limit:
                return $baseQuery($limit)->get();
            default:
                return $articles->merge($baseQuery($countArticlesNeed)->get());
        }
    }

    public function getClauses(Article $article): array
    {
        return [
            'exceptOwnId'     => ['column' => 'id', 'operator' => '!=', 'value' => $article->id],
            'filterByHeading' => ['column' => 'heading_id', 'operator' => '=', 'value' => $article->heading_id]
        ];
    }
}
