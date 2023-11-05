<?php

namespace App\Exceptions\Article;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleException extends Exception
{
    public function __construct(string $message = '', int $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        if ($this->reportable()) {
            Log::channel('articles-import')->error($this->getMessage(), ['exception' => $this]);
        }
    }

    public function reportable(): bool
    {
        return false;
    }
}
