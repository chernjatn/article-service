<?php

namespace App\Models;

use App\Models\Traits\HasChannel;
use App\Models\Ultrashop\TradeName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithMediaBase;

class Article extends Model implements HasMedia
{
    use HasFactory, HasChannel, InteractsWithMediaBase;

    protected $fillable = [
        'title',
        'content',
        'channel',
        'author_id',
        'heading_id',
        'slug',
        'excerpt',
        'product_ids',
        'status',
        'in_slider',
        'noindex',
        'wp_article_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'noindex' => 'boolean',
        'in_slider' => 'boolean',
        'product_ids' => 'array',
    ];

    public function isExported(): bool
    {
        return !is_null($this->wp_article_id);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function seo(): BelongsTo
    {
        return $this->belongsTo(Seo::class);
    }

    public function heading(): BelongsTo
    {
        return $this->belongsTo(Heading::class);
    }

    public function readAlso(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'article_read_also', 'article_id', 'read_also_id');
    }

    public function tradeNames(): BelongsToMany
    {
        return $this->belongsToMany(TradeName::class, 'article_trade_name');
    }

    public function scopeCompact(Builder $query): void
    {
        $query->select([
            'id',
            'title',
            'author_id',
            'heading_id',
            'slug',
            'excerpt',
            'status',
            'in_slider',
            'noindex',
            'created_at'
        ]);
    }

    protected static function booted()
    {
        if (request()->wantsJson()) {
            static::addGlobalScope('is_active', function (Builder $builder) {
                $builder->where('status', true);
            });
        }
    }
}
