<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\Wp\Exceptions\ExportArticleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArticleDelete implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(protected Article $article)
    {
    }

    public function handle(): void
    {
        try {
            articleService()->deleteArticle($this->article->wp_article_id);
        } catch (\Throwable $exc) {
            (new ExportArticleException($exc->getMessage(), (int) $exc->getCode(), $exc))->report();
        }
    }

    public function uniqueId(): string
    {
        return $this->article->id;
    }
}
