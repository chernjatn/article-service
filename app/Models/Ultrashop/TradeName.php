<?php

namespace App\Models\Ultrashop;

use App\Models\Article;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TradeName extends UltrashopModel
{
    public function banners(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_trade_names');
    }
}
