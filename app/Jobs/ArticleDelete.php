<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\Wp\Exceptions\DeleteArticleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArticleDelete implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const QUEUE = 'article_delete';
    public int $tries = 2;

    public function __construct(protected int $wp_article_id)
    {
        $this->queue = self::QUEUE;
    }

    public function handle(): void
    {
        try {
            articleService()->deleteArticle($this->wp_article_id);
        } catch (\Throwable $exc) {
            (new DeleteArticleException($exc->getMessage(), (int) $exc->getCode(), $exc))->report();
        }
    }
}
