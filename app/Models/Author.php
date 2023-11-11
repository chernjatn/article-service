<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithMediaBase;

class Author extends Model implements HasMedia
{
    use HasFactory, InteractsWithMediaBase;

    protected $fillable = [
        'first_name',
        'last_name',
        'status',
        'gender',
        'speciality',
        'place_of_work',
        'education',
        'experience'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
