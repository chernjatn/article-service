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
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'in_slider' => $this->in_slider,
            'created_at' => $this->created_at->format('d.m.Y'),
            'heading' => new HeadingResource($this->heading),
            'image_url' => $this->getFirstMediaUrl(),
        ];
    }
}
