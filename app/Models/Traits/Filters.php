<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filters
{
    public function scopeApplyFilters(Builder $query): void
    {
        $query->with('heading')
            ->when(request()->has('in_slider'), function (Builder $q) {
                return $q->where('in_slider', request()->boolean('in_slider'));
            })
            ->when(request()->has('heading_id'), function (Builder $query) {
                return $query->whereHas('heading', function ($q) {
                    $q->where('id', request()->integer('heading_id'));
                });
            })
            ->orderByDesc('created_at');
    }
}
