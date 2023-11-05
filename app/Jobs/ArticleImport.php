<?php

namespace App\Jobs;

use app\Abstracts\ImportJob;
use App\Services\Wp\DTO\Contracts\Article as ArticleContract;
use App\Services\Entity\UpdateArticle;

class ArticleImport extends ImportJob
{
    const QUEUE = 'articles_import';

    public static function getQueueName(): string
    {
        return self::QUEUE;
    }

    public function handle(): void
    {
        $this->beforeHandle();

        $this->importService->articlesPage($this->page)->each(static function (ArticleContract $post) {
            UpdateArticle::process($post);
        });
    }
}
