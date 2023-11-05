<?php

use App\Services\Wp\ArticleService;

function article(): ArticleService
{
    return app(ArticleService::class);
}
