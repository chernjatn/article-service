<?php

namespace App\Models;

use App\Enums\Channel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithMediaBase;

class Article extends Model implements HasMedia
{
    /**
     * @property int $id
     * @property int $wp_article_id
     * @property bool $status
     * @property Carbon $created_at
     */
    use HasFactory, InteractsWithMediaBase;

    protected $fillable = [
        'title',
        'content',
        'channel_id',
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

    public function heading(): BelongsTo
    {
        return $this->belongsTo(Heading::class);
    }

    protected static function booted()
    {
        if (request()->wantsJson()) {
            static::addGlobalScope('api', function (Builder $builder) {
                $builder->where('status', true)
                    ->when(request()->has('in_slider'), function (Builder $q) {
                        return $q->where('in_slider', request()->boolean('in_slider'));
                    })
                    ->when(request()->has('heading_id'), function (Builder $query) {
                        return $query->whereHas('heading', function ($q) {
                            $q->where('id', request()->integer('heading_id'));
                        });
                    });
            });

            static::addGlobalScope('channel', function (Builder $builder) {
                $channelCode = request()?->header('X-Channel');

                if ($channelCode) {
                    $channel_id = Channel::getId($channelCode);

                    if ($channel_id) {
                        $builder->where('channel_id', $channel_id);
                    }
                }
            });
        }
    }
}
