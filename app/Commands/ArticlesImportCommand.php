<?php

namespace App\Commands;

use Throwable;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Article\ArticlesImportException;
use App\Jobs\ArticlesImport;

class ArticlesImportCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'articles-update';
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
            $articlesService = ArticleService();

            for ($page = 1; $page <= $articlesService->articlesPageCount(); $page++) {
                dispatch_sync(new ArticlesImport($articlesService, $this->version, $page));
            }

        } catch (Throwable $exc) {
            Log::error($exc->getMessage());
            throw new ArticlesImportException($exc->getMessage(), $exc->getCode(), $exc);
        }
    }
}
