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
            'status' => $this->status,
            'is_special' => $this->is_special,
            'noindex' => $this->noindex,
            'created_at' => $this->created_at,
            'excerpt' => $this->excerpt,
            'image' => $this->getFirstMediaUrl(),
        ];
    }
}
