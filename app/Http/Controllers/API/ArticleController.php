<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Queries\Article\FilterQuery;
use App\Models\Article;
use App\Resource\ArticleDetailResource;
use App\Resource\ArticleResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    const COUNT_ON_TRADE_NAME = 4;

    public function index(FilterQuery $filterQuery, Request $request): AnonymousResourceCollection
    {
        $articles = $filterQuery->paginate($request->input('per_page'));

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): ArticleDetailResource
    {
        return new ArticleDetailResource($article);
    }

    public function articlesTradeName(FilterQuery $filterQuery): AnonymousResourceCollection
    {
        $articles = $this->getArticlesTradeName($filterQuery);

        return ArticleResource::collection($articles);
    }

    public function getArticlesTradeName(FilterQuery $filterQuery): Collection
    {
        $result = $filterQuery->take(self::COUNT_ON_TRADE_NAME)->get();

        $baseQuery = fn (int $limit = self::COUNT_ON_TRADE_NAME) => Article::query()
            ->whereNotIn('id', $result->pluck('id'))
            ->limit($limit);

        $countArticlesNeed = self::COUNT_ON_TRADE_NAME - $result->count();

        switch ($countArticlesNeed) {
            case 0:
                return $result;
            case self::COUNT_ON_TRADE_NAME:
                return $baseQuery()->get();
            default:
                return $result->merge($baseQuery($countArticlesNeed)->get());
        }
    }
}
