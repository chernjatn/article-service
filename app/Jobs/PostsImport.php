<?php

namespace App\Jobs;

use app\Abstracts\ImportJob;
use App\Contracts\DTO\Post as PostContract;
use Ultra\Shop\Services\Entity\AddPost;

class PostsImport extends ImportJob
{
    const QUEUE = 'posts_import';

    public static function getQueueName(): string
    {
        return self::QUEUE;
    }

    public function handle(): void
    {
        $this->beforeHandle();

        $this->importService->postsPage($this->page)->each(static function (PostContract $post) {
            AddPost::process($post);
        });
    }
}
