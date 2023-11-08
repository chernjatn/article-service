<?php

namespace App\Services\Contracts;

use App\Services\Connection\Contracts\Connection;

interface ImportService
{
    public function connection(): Connection;
}
