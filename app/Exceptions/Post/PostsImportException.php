<?php

namespace App\Exceptions\Post;

use Exception;
use Illuminate\Support\Facades\Log;

class PostsImportException extends Exception
{
    public function report()
    {
        Log::channel('posts-import')->error($this->getMessage(), ['exception' => $this]);
    }
}
