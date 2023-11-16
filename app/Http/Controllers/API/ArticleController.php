<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Resource\ArticleDetailResource;
use App\Resource\ArticleResource;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->input('per_page');

        $articles = Article::applyFilters($request)->paginate($perPage);

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): ArticleDetailResource
    {
        return new ArticleDetailResource($article);
    }
}
