<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

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
