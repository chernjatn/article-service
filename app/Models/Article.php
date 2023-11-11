<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithMediaBase;

class Article extends Model
{
    use HasFactory, InteractsWithMediaBase;

    protected $fillable = [
        'title',
        'content',
        'heading',
        'channel_id',
        'author_id',
        'good_ids',
        'status',
        'noindex',
        'author',
        'wp_article_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'noindex' => 'boolean',
        'good_ids' => 'array',
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    protected static function booted()
    {
        if (request()->wantsJson()) {
            static::addGlobalScope('active', function (Builder $builder) {
                $builder->where('status', true);
            });
            static::addGlobalScope('channel', function (Builder $builder) {
                $builder->where('channel_id', request()->integer('channel_id'));
            });
        }
    }
}
