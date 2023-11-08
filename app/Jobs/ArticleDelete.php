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
        $this->queue = 'delete_article';
        $this->onQueue('wp_delete');
    }

    public function handle(): void
    {
        try {
//
//            articleService()->deleteArticle($this->article->wp_article_id);
//            $wpArticle = articleService()->createArticle($test);
//
//            $this->article->update(['wp_article_id' => $wpArticle->getId()]);
        } catch (\Throwable $exc) {
            (new ExportArticleException($exc->getMessage(), (int) $exc->getCode(), $exc))->report();
        }
    }

    public function uniqueId(): string
    {
        return $this->article->id;
    }
}
