<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\Wp\Exceptions\ExportArticleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class ArticleExport implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const QUEUE = 'article_export';

    public int $tries = 2;

    public function __construct(protected Article $article)
    {
        $this->queue = self::QUEUE;
    }

    public function handle(): void
    {
        try {
            $wpArticle = articleService()->createArticle(['title' => $this->article->title]);

            $this->article->update(['wp_article_id' => $wpArticle->getId()]);
        } catch (\Throwable $exc) {
            (new ExportArticleException($exc->getMessage(), (int) $exc->getCode(), $exc))->report();
        }
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->article->id))->dontRelease()];
    }
}
