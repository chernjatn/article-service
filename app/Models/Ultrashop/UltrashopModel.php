<?php

namespace App\Models\Ultrashop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class UltrashopModel extends Model
{
    use HasFactory;

    protected const TABLE_PREFIX = 'ultrashop_';

    protected $fillable = [
        'id',
        'name',
    ];

    public function getTable(): string
    {
        return $this->table
            ?? self::TABLE_PREFIX . Str::snake(Str::pluralStudly(class_basename($this)));
    }
}
