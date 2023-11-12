<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Heading extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_heading');
    }

    protected static function booted()
    {
        if (request()->wantsJson()) {
            static::addGlobalScope('api', function (Builder $builder) {
                $builder->whereHas('articles');
            });
        }
    }
}
