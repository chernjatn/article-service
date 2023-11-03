<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Resource\ArticleDetailResource;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::paginate($request->integer('perPage'));

        return collect([
            'articles' => $articles,
        ]);
    }

    public function show(Article $article)
    {
        return new ArticleDetailResource($article);
    }

}
