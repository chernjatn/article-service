<?php

use App\Services\Wp\ArticleService;

function articleService(): ArticleService
{
    return app(ArticleService::class);
}
