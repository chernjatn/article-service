<?php

namespace App\Services\Wp\Jobs;

use App\Services\Entity\UpdateArticle;
use App\Services\Wp\DTO\Contracts\Article as ArticleContract;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArticlesImport
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const QUEUE = 'articles_import';

    public function __construct(protected int $page = 0)
    {
        $this->queue = self::QUEUE;
    }

    public function handle(): void
    {
        articleService()->articlesPage($this->page)->each(static function (ArticleContract $articleDTO) {
            UpdateArticle::process($articleDTO);
        });
    }
}
