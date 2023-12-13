<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithMediaBase;

class Author extends Model implements HasMedia
{
    use HasFactory, InteractsWithMediaBase;

    protected $fillable = [
        'first_name',
        'last_name',
        'second_name',
        'slug',
        'status',
        'gender',
        'speciality',
        'place_of_work',
        'education',
        'experience'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function seo(): BelongsTo
    {
        return $this->belongsTo(Seo::class);
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
