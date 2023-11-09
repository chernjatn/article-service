<?php

namespace App\Observers;

use App\Jobs\ArticleDelete;
use App\Jobs\ArticleExport;
use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        ArticleExport::dispatch($article)->afterCommit();
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        ArticleExport::dispatchIf(!$article->isExported(), $article)->afterCommit();
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        ArticleDelete::dispatch($article)->afterCommit();
    }
}
