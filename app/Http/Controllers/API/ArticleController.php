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

    public function readAlso(Article $article): AnonymousResourceCollection
    {
        $result = $article->readAlso;

        $clauses = [
            ['column' => 'id', 'operator' => '!=', 'value' => $article->id],
            ['column' => 'heading_id', 'operator' => '=', 'value' => $article->heading_id]
        ];

        $articles = articleRepository()->getArticlesByCountNeed(self::COUNT_ON_READ_ALSO, $result, $clauses);

        return ArticleResource::collection($articles);
    }

    public function articlesTradeName(FilterQuery $filterQuery): AnonymousResourceCollection
    {
        $result = $filterQuery->take(self::COUNT_ON_TRADE_NAME)->get();

        $articles = articleRepository()->getArticlesByCountNeed(self::COUNT_ON_TRADE_NAME, $result);

        return ArticleResource::collection($articles);
    }
}
