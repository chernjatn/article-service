<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Queries\Article\FilterQuery;
use App\Models\Article;
use App\Resource\ArticleDetailResource;
use App\Resource\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    const COUNT_ON_TRADE_NAME = 4;
    const COUNT_ON_READ_ALSO  = 6;

    public function index(FilterQuery $filterQuery, Request $request): AnonymousResourceCollection
    {
        $articles = $filterQuery
            ->paginate($request->input('per_page'));

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): ArticleDetailResource
    {
        return new ArticleDetailResource($article);
    }

    public function articlesReadAlso(Article $article): AnonymousResourceCollection
    {
        $clauses = articleRepository()->getClauses($article);

        $articles = articleRepository()->getArticlesByCountNeed(self::COUNT_ON_READ_ALSO, $article->readAlso, $clauses);

        if ($articles->count() !== self::COUNT_ON_READ_ALSO) {
            $articles = articleRepository()->getArticlesByCountNeed(self::COUNT_ON_READ_ALSO, $articles, $clauses['exceptOwnId']);
        }

        return ArticleResource::collection($articles);
    }

    public function articlesTradeName(FilterQuery $filterQuery): AnonymousResourceCollection
    {
        $result = $filterQuery->take(self::COUNT_ON_TRADE_NAME)->get();

        $articles = articleRepository()->getArticlesByCountNeed(self::COUNT_ON_TRADE_NAME, $result);

        return ArticleResource::collection($articles);
    }
}
