<?php

namespace App\Commands;

use Throwable;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Post\PostsImportException;
use App\Jobs\PostsImport;

class PostsImportCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'posts-update';
    protected $description = '';
    protected int $version;

    public function __construct()
    {
        parent::__construct();
        $this->version = mt_rand(11111, 55555);
    }

    public function handle()
    {
        try {
            $postService = post();

            for ($page = 1; $page <= $postService->postsPageCount(); $page++) {
                dispatch_sync(new PostsImport($postService, $this->version, $page));
            }

        } catch (Throwable $exc) {
            Log::error($exc->getMessage());
            throw new PostsImportException($exc->getMessage(), $exc->getCode(), $exc);
        }
    }
}
