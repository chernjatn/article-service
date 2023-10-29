<?php

namespace App\Commands;

use Throwable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Post\PostsImportException;
use App\Jobs\PostsImport;

class PostsImportCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'posts-import';
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

            $batch = [];

            for ($page = 1; $page <= $postService->postsPageCount(); $page++) {
                $batch[] = (new PostsImport($postService, $this->version, $page));
            }

            $version = $this->version;

            Bus::batch($batch)->name(PostsImport::QUEUE)->onQueue(PostsImport::QUEUE)
                ->then(static function () use ($postService, $version) {
                    dispatch_sync(new AfterPostsImport($postService, $version));
                })
                ->catch(static function (Batch $batch, Throwable $e) {
                    //telegramSender()->sendThrowable($e, 'Ошибка импорта статей');
                })
                ->dispatch();

        } catch (Throwable $exc) {
            Log::error($exc->getMessage());
            throw new PostsImportException($exc->getMessage(), $exc->getCode(), $exc);
        }
    }
}
