<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithMediaBase;

class Article extends Model implements HasMedia
{
    use HasFactory, InteractsWithMediaBase;

    protected $fillable = [
        'title',
        'content',
        'channel_id',
        'author_id',
        'product_ids',
        'status',
        'noindex',
        'wp_article_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'noindex' => 'boolean',
        'product_ids' => 'array',
    ];

    public function isExported(): bool
    {
        return !is_null($this->wp_article_id);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function headings(): BelongsToMany
    {
        return $this->belongsToMany(Heading::class, 'article_heading');
    }

    protected static function booted()
    {
        if (request()->wantsJson()) {
            static::addGlobalScope('api', function (Builder $builder) {
                $builder->where('status', true)
                    ->where('channel_id', request()->integer('channel_id'));
            });
        }
    }
}
