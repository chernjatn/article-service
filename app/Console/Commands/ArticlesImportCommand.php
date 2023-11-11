<?php

namespace App\Console\Commands;

use App\Exceptions\Article\ArticlesImportException;
use App\Jobs\ArticlesImport;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticlesImportCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'articles-import';
    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            for ($page = 1; $page <= ArticleService()->articlesPageCount(); $page++) {
                dispatch_sync(new ArticlesImport($page));
            }
        } catch (Throwable $exc) {
            Log::error($exc->getMessage());
            throw new ArticlesImportException($exc->getMessage(), $exc->getCode(), $exc);
        }
    }
}
