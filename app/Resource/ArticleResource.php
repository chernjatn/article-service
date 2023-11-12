<?php

namespace App\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'in_slider' => $this->in_slider,
            'noindex' => $this->noindex,
            'created_at' => $this->created_at->format('d.m.Y'),
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}
