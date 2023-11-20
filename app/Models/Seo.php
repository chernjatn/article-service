<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seo';

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'header',
        'description'
    ];
}
