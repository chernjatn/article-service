<?php

namespace App\Services\Wp;

use App\Services\Connection\Contracts\ConnectionManager;

class WpConnectionManager implements ConnectionManager
{
    private static ?Wp $wpConnection = null;

    public static function getConnection(): Wp
    {
        $config = self::getConfig();

        return self::$wpConnection ??= new Wp($config['url']);
    }

    public static function getConfig(): array
    {
        return config('wp');
    }
}
