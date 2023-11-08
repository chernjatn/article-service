<?php

namespace App\Observers;

use App\Jobs\ArticleExport;
use App\Models\Article;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        dispatch(new ArticleExport($article));
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        dispatch(new ArticleExport($article));
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }
}
