<?php

namespace App\Filament\Resources;

use App\Services\Wp\WpConnectionManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

trait SettingComponentTrait
{
    public static function createLink(string $url, string $slot): HtmlString
    {
        $config = WpConnectionManager::getConfig();

        return new HtmlString(Blade::render('filament::components.link', [
            'color' => 'primary',
            'href' => $url,
            'target' => '_blank',
            'slot' => $slot,
            'icon' => 'heroicon-o-arrow-top-right-on-square',
        ]));
    }
}
