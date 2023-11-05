<?php

namespace App\Observers;

use App\Jobs\ExportArticle;
use App\Models\Article;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ArticleObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        ExportArticle::dispatch($article);
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        ExportArticle::dispatch($article);
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }

}
