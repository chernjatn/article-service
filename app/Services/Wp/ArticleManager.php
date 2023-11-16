<?php

namespace App\Services\Wp;

use App\Services\Entity\UpdateArticle;
use App\Services\Wp\Jobs\ArticleExport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ArticleManager
{
    public static function importArticle(Model $article): Model
    {
        if (!$article->isExported()) {
            return self::exportArticle($article);
        }

        $lock = Cache::lock('articleimport:article' . $article->id, 60);

        if (!$lock->get()) return $article;

        $importArticle = articleService()->article($article->wp_article_id);

        if (is_null($importArticle)) return $article;

        UpdateArticle::process($importArticle);

        return $article;
    }

    public static function exportArticle(Model $article): Model
    {
        ArticleExport::dispatch($article);

        return $article;
    }
}
