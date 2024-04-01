<?php

use App\Services\Wp\ArticleService;
use App\Models\Repository\ArticleRepository;

function articleService(): ArticleService
{
    return app(ArticleService::class);
}

function articleRepository(): ArticleRepository
{
    return app(ArticleRepository::class);
}
