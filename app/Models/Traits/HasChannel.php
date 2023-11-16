<?php

namespace App\Models\Traits;

use App\Enums\Channel;
use Illuminate\Database\Eloquent\Builder;

trait HasChannel
{
    private string $channelField = 'channel';

    public function initializeHasChannel(): void
    {
        if (! isset($this->casts[$this->channelField])) {
            $this->casts[$this->channelField] = Channel::class;
        }
    }

    public static function bootHasChannel(): void
    {
        static::addGlobalScope('channel', function (Builder $builder) {
            $channelCode = request()?->header('X-Channel');

            if ($channelCode) {
                $channel = Channel::tryFrom($channelCode);

                if ($channel) {
                    $builder->ofChannel($channel);
                }
            }
        });
    }

    public function scopeOfChannel(Builder $query, string|Channel $channel): void
    {
        $query->where('channel', $channel);
    }
}
