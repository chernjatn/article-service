<?php

namespace App\Commands;

use App\Abstracts\ImportJob;
use App\Models\Post;

class AfterPostsImport extends ImportJob
{
    const QUEUE = 'after_posts_import';
    public $timeout = 600;

    public static function getQueueName(): string
    {
        return self::QUEUE;
    }

    public function handle()
    {
        $this->beforeHandle();

        Post::query()->withoutGlobalScopes()
            ->where('version', '!=', $this->version)
            ->delete();
    }
}
