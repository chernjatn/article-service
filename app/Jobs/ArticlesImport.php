<?php

namespace App\Jobs;

use app\Abstracts\ImportJob;
use App\Services\Wp\DTO\Contracts\Article as ArticleContract;
use App\Services\Entity\UpdateArticle;

class ArticlesImport extends ImportJob
{
    const QUEUE = 'articles_import';

    public static function getQueueName(): string
    {
        return self::QUEUE;
    }

    public function handle(): void
    {
        articleService()->articlesPage($this->page)->each(static function (ArticleContract $articleDTO) {
            UpdateArticle::process($articleDTO);
        });
    }
}
