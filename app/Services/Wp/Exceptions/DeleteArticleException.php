<?php

namespace App\Services\Wp\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class DeleteArticleException extends Exception
{
    public function report()
    {
        Log::channel('delete-article')->error($this->getMessage(), ['exception' => $this]);
    }
}
