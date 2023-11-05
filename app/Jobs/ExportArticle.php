<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\Wp\Exceptions\ExportArticleExceptions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportArticle implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(protected Article $article)
    {
        $this->queue = 'export_article';
        $this->onQueue('wp_export');
    }

    public function handle(): void
    {
        try {
            $wpArticle = articleService()->createArticle($this->article);

            $this->article->update(['wp_article_id' => $wpArticle->getId()]);
        } catch (\Throwable $exc) {
            throw new ExportArticleExceptions($exc->getMessage(), (int) $exc->getCode(), $exc);
        }
    }

    public function uniqueId(): string
    {
        return $this->article->id;
    }
}
