<?php

namespace App\Services\Wp;

use App\Exceptions\Article\ArticlesImportException;
use App\Services\Entity\UpdateArticle;
use App\Services\Wp\Jobs\ArticleExport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ArticleManager
{
    public static function importArticle(Model $article): Model
    {
        if (!$article->isExported()) return self::exportArticle($article);

        $lock = Cache::lock('articleimport:article' . $article->id, 60);

        if (!$lock->get()) return $article;

        try {
            $importedArticle = articleService()->article($article->wp_article_id);

            if (is_null($importedArticle)) return $article;

            UpdateArticle::process($importedArticle);
        } catch (\Throwable $exc) {
            (new ArticlesImportException($exc->getMessage(), $exc->getCode(), $exc))->report();

            return $article;
        }

        return $article->refresh();
    }

    public static function exportArticle(Model $article): Model
    {
        ArticleExport::dispatch($article);

        return $article;
    }
}
