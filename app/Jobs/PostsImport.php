<?php

namespace App\Jobs;

use app\Abstracts\ImportJob;
use App\Services\Wp\DTO\Contracts\Post as PostContract;
use App\Services\Entity\UpdatePost;

class PostsUpdate extends ImportJob
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
            UpdatePost::process($post);
        });
    }
}
