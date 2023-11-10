<?php

namespace App\Services\Wp;

use App\Services\Entity\UpdateArticle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ArticleManager
{
    public static function importArticle(Model $article): Model
    {
        $lock = Cache::lock('articleimport:article' . $article->id, 60);

        if (!$lock->get()) return $article;

        $importArticle = articleService()->article($article->wp_article_id);

        if (is_null($importArticle)) return $article;

        UpdateArticle::process($importArticle);

        return $article;
    }
}
