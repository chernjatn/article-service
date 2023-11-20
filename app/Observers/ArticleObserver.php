<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\Wp\Jobs\ArticleDelete;
use App\Services\Wp\Jobs\ArticleExport;

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
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        ArticleDelete::dispatchSync($article->wp_article_id)->afterCommit();
    }
}
