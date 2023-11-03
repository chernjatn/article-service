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
            'heading' => $this->heading,
            'status' => $this->status,
            'noindex' => $this->noindex,
            'created_at' => $this->created_at,
            'author' => $this->author,
            'image' => $this->base_image,
        ];
    }
}
