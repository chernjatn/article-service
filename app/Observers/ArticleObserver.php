<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ArticleObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        if (is_null($article->wp_article_id)) {
            $wpArticle = article()->createArticle($article);

            $article->wp_article_id = $wpArticle['id'];

            $article->save();
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        if (is_null($article->wp_article_id)) {
            $wpArticle = article()->createArticle($article);

            $article->wp_article_id = $wpArticle['id'];

            $article->save();
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
