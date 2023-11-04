<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\Wp\ArticleService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ArticleObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        $articleService = new ArticleService();

        $articleService->createArticle($article);
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        $articleService = new ArticleService();

        if(is_null($article->wp_article_id)){
            $articleService->createArticle($article);
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
