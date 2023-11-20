<?php

namespace App\Services\Connection\Contracts;

interface ConnectionManager
{
    public static function getConfig(): array;
}
