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
    public function index(FilterQuery $filterQuery, Request $request): AnonymousResourceCollection
    {
        $articles = $filterQuery->paginate($request->input('per_page'));

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): ArticleDetailResource
    {
        return new ArticleDetailResource($article);
    }
}
