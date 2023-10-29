<?php

namespace App\Exceptions\Post;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class PostException extends Exception
{
    public function __construct(string $message = '', int $code = Response::HTTP_UNPROCESSABLE_ENTITY, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function report()
    {
        if ($this->reportable()) {
            Log::channel('posts-import')->error($this->getMessage(), ['exception' => $this]);
        }
    }

    public function reportable(): bool
    {
        return false;
    }
}
