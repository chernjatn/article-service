<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'heading',
        'channel_id',
        'status',
        'noindex',
        'author'
    ];

    protected $casts = [
        'status'  => 'boolean',
        'index'   => 'boolean',
    ];
}
