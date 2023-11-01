<?php

use App\Services\Wp\PostService;

function post(): PostService
{
    return app(PostService::class);
}
