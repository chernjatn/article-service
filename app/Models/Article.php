<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'heading',
        'channel_id',
        'status',
        'noindex',
        'author',
        'wp_article_id'
    ];

    protected $casts = [
        'status'  => 'boolean',
        'noindex' => 'boolean',
    ];

    public function isExported(): bool
    {
        return !is_null($this->wp_article_id);
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
